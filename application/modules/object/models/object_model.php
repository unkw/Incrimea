<?php
class Object_Model extends CI_Model {
    
    /** Дополнительные поля */
    private $additional_fields = null;

    public function __construct() {
        parent::__construct();
    }

    public function get_count_all()
    {
        return $this->db->count_all('objects');
    }

    /** Получить список страниц */
    public function get_list($num, $offset)
    {
        $this->db
            ->select('o.*, a.alias, u.username, r.name as resort')
            ->from('objects o')
            ->join('resorts r', 'r.id = o.resort_id')
            ->join('alias a', 'a.id = o.alias_id', 'left')
            ->join('users u', 'u.id = o.uid')
            ->order_by('o.created_date', 'desc')
            ->limit($num, $offset);

        return $this->db->get()->result_array();
    }

    /** Получить список всех курортов */
    public function get_resorts()
    {
        return $this->db->select('*')
            ->from('resorts')
            ->order_by('name', 'asc')->get()->result_array();
    }

    /**
     * Дополнительные поля (тип, сервис, инфраструктура, в номерах и т.д.)
     * @param bool $short_select - флаг
     * @return array 
     */
    public function get_addition_fields($short_select = FALSE)
    {
        if ( ! is_null($this->additional_fields) )
            return $this->additional_fields;
        
        // Если выбран тип контента "Отели", то проверяем чекбоксы на кол-во контента
        if ($this->input->get('type', TRUE) == 'objects')
        {
            $sql = $this->getListSQL($short_select);
            $fields = $this->db->query(implode(' UNION ', $sql))->result_array();             
        } 
        
        else
        {
            $fields = $this->db->get_where('obj_fields')->result_array();
        }
        
        $data = array();
        foreach ($fields as $row) {

            switch ($row['field_id']) {

                case 1: $data['beachs'][] = $row; break;
                case 2: $data['room'][] = $row; break;
                case 3: $data['infrastructure'][] = $row; break;
                case 4: $data['entertainment'][] = $row; break;
                case 5: $data['service'][] = $row; break;
                case 6: $data['for_children'][] = $row; break;
                case 7: $data['types'][] = $row; break;
            }
        }
        
        return $this->additional_fields = $data;
    }
    
    /** SQL запрос */
    public function getListSQL($short_select)
    {
        // Подзапрос извлекающий кол-во контента у каждого поля
        $sub_query = 
            'SELECT count(1)'."\n"
            .' FROM objects o'."\n"
            .' JOIN resorts r ON r.id = o.resort_id'."\n"
            .' JOIN obj_fields b ON b.url_name = o.beach_id'."\n"
            .' WHERE o.published = 1';

        // $_GET параметры фильтров
        $params = $this->form_params();

        // Наложение дополнительных условий на подзапрос
        $add_conditions = array();
        foreach ($params as $key => $value) {

            if ( empty($params[$key]) )
                continue;

            switch ($key) {

                // Условие вида cond1 LIKE val1 AND cond2 LIKE val2 ... AND condN LIKE valN 
                case 'room': case 'infr': case 'service': case 'entment': case 'child':

                    $json_fields = array(
                        'room' => 'room',
                        'infr' => 'infrastructure',
                        'service' => 'service',
                        'entment' => 'entertainment',
                        'child' => 'for_children',
                    );                    

                    foreach ($value as $val)
                        $add_conditions[] = "o.".$json_fields[$key]." LIKE '%".$this->db->escape_like_str($val)."%'";
                    break;

                case 'distance': case 'price_min': case 'price_max':
                    $border_fields = array(
                        'distance' => array('o.beach_distance', '<='),
                        'price_min' => array('o.price', '>='),
                        'price_max' => array('o.price', '<='),
                    );

                    $add_conditions[] = $border_fields[$key][0].' '.$border_fields[$key][1].' '.(int)$value;
                    break;
            }
        }

        $sub_query .= $add_conditions ? ' AND ' . implode(' AND ', $add_conditions) : '';

        /** Собираем ссновной запрос */
        $sql = array();
        $filters = array(
            'beachs' => array('id'=>1, 'col'=>'beach_id'),
            'room' => array('id'=>2, 'col'=>'room'),
            'infr' => array('id'=>3, 'col'=>'infrastructure'),
            'entment' => array('id'=>4, 'col'=>'entertainment'),
            'service' => array('id'=>5, 'col'=>'service'),
            'child' => array('id'=>6, 'col'=>'for_children'),
        );
        
        $select = $short_select ? 'f.field_id, f.url_name' : 'f.*';
        foreach ($filters as $key => $val) {

            switch ($key) {
                case 'beachs':
                    $sub_query .= !empty($params[$key]) ? ' AND o.'.$val['col'].' = f.url_name' : '';
                    $sql[] = '(SELECT '.$select.', ('.$sub_query.') as count'
                        .' FROM obj_fields f'
                        .' WHERE f.field_id = '.$val['id'].')';                    
                    break;
                default:
                    $sql[] = '(SELECT '.$select.', ('.$sub_query.' AND o.'.$val['col']." LIKE CONCAT(\"%\", f.url_name ,\"%\")) as count"
                        .' FROM obj_fields f'
                        .' WHERE f.field_id = '.$val['id'].')';  
            }
        }
        
        return $sql;
    }
    
    /** Параметры GET запроса для отелей */
    private function form_params()
    {
        $params = $this->input->get(NULL, TRUE);
        
        return array(
            'type' => isset($params['type']) ? $params['type'] : false,
            'resorts' => isset($params['resorts']) ? explode(',', $params['resorts']) : array(),            
            'room' => isset($params['room']) ? explode(',', $params['room']) : array(),
            'infr' => isset($params['infr']) ? explode(',', $params['infr']) : array(),
            'service' => isset($params['service']) ? explode(',', $params['service']) : array(),
            'entment' => isset($params['entment']) ? explode(',', $params['entment']) : array(),
            'child' => isset($params['child']) ? explode(',', $params['child']) : array(),
            'beachs' => isset($params['beachs']) ? explode(',', $params['beachs']) : array(),
            'distance' => isset($params['distance']) ? $params['distance'] : '',
            'price_min' => isset($params['p-min']) ? $params['p-min'] : '',
            'price_max' => isset($params['p-max']) ? $params['p-max'] : '',
        );
    }    

    /** Получить полные данные json полей по url идентификатору */
    public function value_from_additional_field($field, $json)
    {
        $data = $this->get_addition_fields();

        $output = array();

        if (isset($data[$field]))
        {
            foreach ($data[$field] as $row)
            {
                if (in_array($row['url_name'], $json))
                    $output[] = $row;
            }
        }

        return $output;
    }

    /** Получить основные данные отеля для редактирования */
    public function get($id, $published = FALSE)
    {
        $where = array('o.id' => $id);

        $data = $this->db->select('o.*')
            ->from('objects o')
            ->where($where)
            ->get()->row_array();
        
        // Номерной фонд
        $data['room_found'] = $this->input->post('room')
            ? array()
            : $this->db->get_where('obj_rooms', array('obj_id' => $id))->result_array();        

        $data = $this->convert_data_to_view($data);
        
        if ($this->input->post('room'))
            $data['room_found'] = $this->input->post('room');
        
        return $data;
    }

    /** Получить данные объекта для отображения */
    public function get_obj($id)
    {
        $where = array(
            'o.id' => $id,
        );
        
        $data = $this->db->select('o.*, r.name as resort, f1.name as type, f2.name as beach')
            ->from('objects o')
            ->join('resorts r', 'r.id = o.resort_id')
            ->join('obj_fields f1', 'f1.url_name = o.type_id')
            ->join('obj_fields f2', 'f2.url_name = o.beach_id')
            ->where(array(
                'f1.field_id' => 7,
                'f2.field_id' => 1,
            ))
            ->where($where)
            ->get()->row_array();

        $data = $this->convert_data_to_view($data);

        return $data;
    }

    /** Добавить отель */
    public function create($data)
    {
        // Преобразование данных
        $data = $this->convert_data_to_save($data);

        // Сохранение метатегов
        $data['meta_id'] = $this->metatags->create();

        // Сохранение отеля
        $this->db->insert('objects', $data);
        $obj_id = $this->db->insert_id();

        // Сохранение синонима
        $path_data = $this->generate_path($obj_id, $data);
        $alias_id = $this->path->create($path_data);
        $this->db->update('objects', array('alias_id' => $alias_id), array('id' => $obj_id));
        
        // Сохранение комнат
        $this->room_foundation_save($obj_id);
    }

    /**
     * Обновление отеля
     * @param int $obj_id - ID отеля
     * @param array $data - данные
     */
    public function update($obj_id, $data)
    {
        // Преобразование данных
        $data = $this->convert_data_to_save($data);
        
        // Сохранение метатегов
        $this->metatags->update($data['meta_id']);

        // Сохранение основных данных отеля
        $this->db->where('id', $obj_id)
            ->update('objects', $data);
        
        // Сохранение синонима
        $path_data = $this->generate_path($obj_id, $data);
        $this->path->update($data['alias_id'], $path_data);
        
        // Сохранение номерного фонда
        $this->room_foundation_save($obj_id);
    }
    
    /**
     * Удаление отеля
     * @param int $id - ID
     */
    public function delete($id)
    {
        $data = $this->db
            ->query('SELECT alias_id, meta_id FROM objects WHERE id = ?', array($id))
            ->row_array();
        
        // Удаление отеля
        $this->db->delete('objects', array('id' => $id));
        // Удалить синоним в таблице алиасов
        $this->db->delete('alias', array('id' => $data['alias_id']));
        // Удалить метатеги
        $this->db->delete('metatags', array('id' => $data['meta_id']));
        // Удалить номера
        $this->db->delete('obj_rooms', array('obj_id' => $id));
    }
    
    /**
     * Генерация url синонима
     * @param int $obj_id - ID отеля
     * @param array $data - данные
     * @return array
     */
    public function generate_path($obj_id, $data)
    {
        $pathdata = array(
            'realpath' => 'object/view/'.$obj_id,
            'auto' => $this->input->post('pathauto') ? 1 : 0,
        );
        
        if ($pathdata['auto'])
        {
            $resort = $this->db->get_where('resorts', array('id' => $data['resort_id']))->row_array();
            $pathdata['alias'] = array('object', $resort['name'], $data['title']);
        }
        else
        {
            $pathdata['alias'] = array($this->input->post('path'));
        }
        
        return $pathdata;
    }

    /** Сохранение комнат отеля */
    public function room_foundation_save($obj_id)
    {
        $data = $this->input->post('room');
        
        if ( ! $data && ! is_array($data) )
            return FALSE;
        
        $this->db->delete('obj_rooms', array('obj_id' => $obj_id));
        
        $rooms = array();
        foreach ($data as $d) {
            
            $rooms[] = array(
                'obj_id' => $obj_id,
                'title' => $d['title'],
                'num_beds' => $d['num_beds'],
                'num_rooms' => $d['num_rooms'],
                'in_room' => json_encode(isset($d['in_room']) ? $d['in_room'] : array()),
                'tarifs' => json_encode(isset($d['tarifs']) ? $d['tarifs'] : array()),
                'pics' => json_encode(isset($d['pics']) ? $d['pics'] : array()),
            );
        } 
        
        $this->db->insert_batch('obj_rooms', $rooms);
    }

    /** Преобразование данных для сохранения отеля */
    private function convert_data_to_save($data)
    {
        /** Сохраняем значения чекбоксов  в формате json */
        $data['room'] = json_encode($data['room'] ? $data['room'] : array());
        $data['infrastructure'] = json_encode($data['infrastructure'] ? $data['infrastructure'] : array());
        $data['service'] = json_encode($data['service'] ? $data['service'] : array());
        $data['entertainment'] = json_encode($data['entertainment'] ? $data['entertainment'] : array());
        $data['for_children'] = json_encode($data['for_children'] ? $data['for_children'] : array());

        // Преобразование изображений в json формат
        $data['images'] = json_encode($data['images']);
        
        return $data;
    }

    /** Преобразование данных для отображения */
    private function convert_data_to_view($data)
    {
        $data['room'] = json_decode($data['room']);
        $data['infrastructure'] = json_decode($data['infrastructure']);
        $data['service'] = json_decode($data['service']);
        $data['entertainment'] = json_decode($data['entertainment']);
        $data['for_children'] = json_decode($data['for_children']);

        $data['images'] = $data['images'] ? json_decode($data['images']) : array();
        
        // Преобразование данных номерного фонда
        if (isset($data['room_found']) && $data['room_found'])
        {
            foreach ($data['room_found'] as & $room) {
                
                $room['pics'] = json_decode($room['pics'], true);
                $room['in_room'] = json_decode($room['in_room'], true);
                $room['tarifs'] = json_decode($room['tarifs'], true);
            }
        }
        
        return $data;
    }


    /** Ресайз изображения и создание его превьюшки */
    public function create_images($img)
    {
        // Основные параметры
        $default_config = $this->config->config['image_lib'];
        // Оригинальный файл
        $origin_img = 'images/object/large/' . $img['file_name'];

        /** Создание large изображения */
        $size = $this->config->config['image_large'];
        $large_img = $origin_img;
        $config = array();
        $config = array_merge($default_config, $size);
        $config['source_image'] = $large_img;

        $this->load->library('image_lib', $config);
        $this->image_lib->resize();
        $this->image_lib->clear();

        /** Создание medium изображения */
        $size = $this->config->config['image_medium'];
        $medium_img = 'images/object/medium/' . $img['file_name'];
        $config = array();
        $config = array_merge($default_config, $size);
        $config['source_image'] = $large_img;
        $config['new_image'] = $medium_img;

        $this->image_lib->resize_and_crop($config);

        /** Создание превью изображения */
        $size = $this->config->config['thumb'];
        $thumb = 'images/object/thumb/' . $img['file_name'];
        $config = array();
        $config = array_merge($default_config, $size);
        $config['source_image'] = $large_img;
        $config['new_image'] = $thumb;

        $this->image_lib->resize_and_crop($config);
    }
}