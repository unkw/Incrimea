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

        $query = $this->db->get();

        return $query->result_array();
    }

    /** Получить список всех курортов */
    public function get_resorts()
    {
        $this->db->select('*')
            ->from('resorts')
            ->order_by('name', 'asc');

        return $this->db->get()->result_array();
    }

    /** Дополнительные поля (тип, сервис, инфраструктура и т.д.) */
    public function get_addition_fields()
    {
        if ( ! is_null($this->additional_fields) )
            return $this->additional_fields;

        $fields = $this->db->select('*')
            ->from('obj_fields')->get()->result_array();

        $data = array(
            'types' => array(),
            'beachs' => array(),
            'room' => array(),
            'infrastructure' => array(),
            'service' => array(),
            'entertainment' => array(),
            'for_children' => array(),
        );
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

        if ($published)
            $where['o.published'] = 1;

        $this->db->select('o.*')
            ->from('objects o')
            ->where($where);

        $q = $this->db->get();

        $data = $q->row_array();
        
        $data = $this->convert_data_to_view($data);

        return $data;
    }

    /** Получить данные объекта для отображения */
    public function get_obj($id){

        $where = array(
            'o.id' => $id,
            'o.published' => 1,
        );

        $this->db->select('o.*, r.name as resort, f1.name as type, f2.name as beach')
            ->from('objects o')
            ->join('resorts r', 'r.id = o.resort_id')
            ->join('obj_fields f1', 'f1.url_name = o.type_id')
            ->join('obj_fields f2', 'f2.url_name = o.beach_id')
            ->where_in('f1.field_id', array(1, 7))
            ->where_in('f2.field_id', array(1, 7))
            ->where($where);

        $data = $this->db->get()->row_array();

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

        // Сохранение алиаса
        $this->save_path($this->db->insert_id(), $data, TRUE);
    }

    /** Обновить отель */
    public function update($id, $data)
    {
        // Преобразование данных
        $data = $this->convert_data_to_save($data);
        
        // Сохранение метатегов
        if ( ! $this->metatags->update($data['meta_id']) )
            $data['meta_id'] = $this->metatags->create();

        // Сохранение синонима
        $this->save_path($id, & $data);

        $this->db->where('id', $id)
            ->update('objects', $data);
    }

    /** Сохранение синонима */
    private function save_path($id, & $data, $create = FALSE)
    {
        $pathdata = array(
            'realpath' => 'object/view/'.$id,
            'auto'     => $this->input->post('pathauto') ? 1 : 0,
        );

        // Формируем алиас
        $resort = $this->db->get_where('resorts', array('id' => $data['resort_id']))->row_array();
        if (!$pathdata['auto'] && trim($this->input->post('path')))
            $pathdata['alias'] = $this->input->post('path');
        else
            $pathdata['alias'] = 'object/'.$resort['name'].'/'.$data['title'];

        // Сохранение
        if ( $create || !$data['alias_id'] || !$this->path->update($pathdata, $data['alias_id']) )
            $data['alias_id'] = $this->path->create($pathdata);

        // Обновить alias_id контента при создании контента
        if ($create)
            $this->db->update('objects', array('alias_id'=>$data['alias_id']), array('id'=>$id));

        return TRUE;
    }

    /** Преобразование данных для сохранения отеля */
    private function convert_data_to_save($data)
    {
        print_r($data); die;
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