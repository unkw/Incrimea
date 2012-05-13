<?php
class Filter_Model extends CI_Model {
    
    public $module_name = 'filter';
    public $pager = FALSE;
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Получить список определенного заданного контента 
     * в зависимости от типа (отели, статьи или события)
     * @param string $qs - $_GET параметры
     * @param bool $pagination - Флаг: пагинация
     * @param int $per_page - Кол-во выводимого контента на страницу
     * @return array 
     */
    function get_content($qs, $pagination = FALSE, $per_page = 5)
    {
        // Тип контента
        $type = $qs['type'];
        // Конфиги для пагинации
        $config = array();
        // Кол-во выводимого контента на страницу
        $config['per_page'] = $per_page;
        // Номер текущей страницы
        $page = (int)$this->input->get('page') ? (int)$this->input->get('page') : 1;
        // Позиция для БД
        $offset = ($page - 1) * $config['per_page'];

        /** Прикручиваем пагинацию, если необходимо */
        if ($pagination)
        {
            $this->load->library('pagination');
            // Адресная строка
            $config['base_url'] = base_url().$this->module_name.'?'.$this->url_params_to_string($qs);
            // Кол-во всего контента
            $method = 'count_all_'.$type;
            $config['total_rows'] = $this->$method($qs);
            // Ссылка на первую страницу
            $config['first_url'] = base_url().$this->module_name.'?'.$this->url_params_to_string($qs);

            $this->pagination->initialize($config);

            if ($offset > $config['total_rows'])
                redirect('filter');

            // Пейджер
            $this->pager = $this->pagination->create_links();
        }

        $method = 'get_'.$type;

        return $this->$method($qs, $config['per_page'], $offset);
    }    

    /**
     * Получить список отелей
     * @param array $params - $_GET параметры
     * @param int $per_page - Кол-во отелей
     * @param int $offset - Смещение при выборке из БД 
     * @return array 
     */ 
    public function get_objects($params, $per_page, $offset)
    {
        $params = $params ? $params : array();
        
        /** Основные данные отелей */
        $this->db->select('o.id, o.title, o.body, o.images, o.price, o.infrastructure, a.alias, r.name as resort')
            ->from('objects o')
            ->join('obj_fields b', 'b.url_name = o.beach_id')
            ->join('alias a', 'a.id = o.alias_id', 'left')
            ->join('resorts r', 'o.resort_id = r.id');

        // Дополнительные условия
        $this->obj_add_filters($params);

        // Только опубликованные
        $where = array(
            'o.published' => 1
        );        
        
        $data = $this->db->where($where)
            ->order_by('o.priority desc, o.created_date desc')
            ->limit($per_page, $offset)
            ->get()->result_array();
        
        // Дополнительные поля объекта (в номерах, сервис, развлечения и т.п.)
        $addition_fields = $this->object->model->get_addition_fields();

        // Инфраструктура - все чекбоксы
        $infr_all = $addition_fields['infrastructure'];

        /** Преобразование данных */
        foreach ($data as & $obj)
        {
            // Галерея изображений
            $obj['images'] = $obj['images'] ? json_decode($obj['images']) : array();
            
            /** Инфраструктура */
            $obj['infrastructure'] = $obj['infrastructure'] ? json_decode($obj['infrastructure']) : array();
            $_structure = array();
           
            foreach ($infr_all as $s)
            {
                $index = array_search($s['url_name'], $obj['infrastructure']);
                if ($index !== FALSE)
                    $_structure[] = $s;
            }
            $obj['infrastructure'] = $_structure;
        }

        return $data;
    }

    /**
     * Получить список статей
     * @param array $params - $_GET параметры
     * @param int $per_page - Кол-во статей
     * @param int $offset - Смещение при выборке из БД 
     * @return array 
     */ 
    public function get_articles($params, $per_page, $offset)
    {
        $params = $params ? $params : array();

        // Только опубликованные
        $where = array(
            'a.status' => 1
        );

        /** Основные данные статей */
        $this->db->select('a.id, a.title, a.preview, a.image_src, a.image_desc, al.alias, r.name as resort')
            ->from('articles a')
            ->join('alias al', 'al.id = a.alias_id', 'left')
            ->join('resorts r', 'a.resort_id = r.id')
            ->where($where);

        if (isset($params['resorts']))
            $this->db->where_in('r.url_name', explode(',', $params['resorts']));

        $data = $this->db->order_by('a.sticky desc, a.created_date desc')
            ->limit($per_page, $offset)
            ->get()
            ->result_array();

        return $data;
    }

    /** Получить список событий */
    public function get_events($params, $per_page, $offset)
    {
        $params = $params ? $params : array();

        // Только опубликованные
        $where = array(
            'e.status' => 1
        );

        /** Основные данные статей */
        $this->db->select('e.id, e.title, e.preview, e.image_src, e.image_desc, a.alias, r.name as resort')
            ->from('events e')
            ->join('alias a', 'a.id = e.alias_id', 'left')
            ->join('resorts r', 'e.resort_id = r.id')
            ->where($where);

        if (isset($params['resorts']))
            $this->db->where_in('r.url_name', explode(',', $params['resorts']));

        $data = $this->db->order_by('e.sticky desc, e.created_date desc')
            ->limit($per_page, $offset)
            ->get()
            ->result_array();

        return $data;
    }
    
    /** Кол-во всех отелей */
    public function count_all_objects($params)
    {
        $this->db->from('objects o')
            ->join('resorts r', 'r.id = o.resort_id')
            ->join('obj_fields b', 'b.url_name = o.beach_id')
            ->where('o.published', 1);

        $this->obj_add_filters($params);
        
        return $this->db->count_all_results();
    }

    /** Кол-во всех статей */
    public function count_all_articles($params)
    {
        $this->db->from('articles a')->join('resorts r', 'r.id = a.resort_id')->where('a.status', 1);

        if (isset($params['resorts']))
            $this->db->where_in('r.url_name', explode(',', $params['resorts']));

        return $this->db->count_all_results(); 
    }

    /** Кол-во всех событий */
    public function count_all_events($params)
    {
        $this->db->from('events e')->join('resorts r', 'r.id = e.resort_id')->where('e.status', 1);

        if (isset($params['resorts']))
            $this->db->where_in('r.url_name', explode(',', $params['resorts']));

        return $this->db->count_all_results();
    }
    
    /** Условия накладываемые дополнительными фильтрами объектов */
    private function obj_add_filters($params)
    {
        /** Условия для классических полей (связь 1->∞) */
        $fields = array(
            'resorts' => 'r',
            'beachs' => 'b',
        );
        foreach ($fields as $k => $alias)
            $this->condition_for_classic_fields($params, $k, $alias);

        /** Условия для полей содержащих данные в формате JSON */
        $json_fields = array(
            'room' => 'room',
            'infr' => 'infrastructure',
            'service' => 'service',
            'entment' => 'entertainment',
            'child' => 'for_children',
        );
        foreach ($json_fields as $key => $column)
            $this->condition_for_json_fields($params, $key, $column);

        /** Условия для полей на проверку больше/меньше */
        $border_fields = array(
            'distance' => array('o.beach_distance', '<='),
            'p-min' => array('o.price', '>='),
            'p-max' => array('o.price', '<='),
        );
        foreach ($border_fields as $key => $val)
            $this->condition_for_border_fields($params, $key, $val);
        
        return TRUE;
    }

    /** Условия для обычных полей (один ко многим) */
    private function condition_for_classic_fields($params, $name, $alias)
    {
        if (isset($params[$name])) {
            $this->db->where_in($alias.'.url_name', explode(',', $params[$name]));

            return TRUE;
        }

        return FALSE;
    }

    /** Условия для полей содержащих JSON */
    private function condition_for_json_fields($params, $name, $column)
    {
        if (isset($params[$name])) {
            $data = explode(',', $params[$name]);
            foreach ($data as $d)
                $this->db->like('o.'.$column, '"'.$d.'"');

            return TRUE;
        }

        return FALSE;
    }

    /** 
     * Условия для полей являющихся границей, для сравнения
     * с ними на больше или меньше (цена, расстояние до пляжа и т.п.)
     * @param array $params - параметры get запроса
     * @param string $name - имя параметра из get запроса
     * @param array $val - массив содержащий имя колонки и знак сравнения
     * return bool
     */
    private function condition_for_border_fields($params, $name, $val)
    {
        if (isset($params[$name])) {
            $this->db->where($val[0].' '.$val[1], (int)$params[$name]);

            return TRUE;
        }

        return FALSE;
    }
    
    /**
     * Преобразование ассоциативного массива в GET строку
     * @param array $qs Параметры для преобразования в строку
     * @param string $sep_in Разделить между ключом и значением параметра
     * @param string $sep_out Символ, по которому необходимо склеить параметры
     * @return string 
     */
    function url_params_to_string($qs, $sep_in = '=', $sep_out = '&')
    {
        $temp = array();

        foreach ($qs as $key => $value)
        {
            if ($key != 'page')
                $temp[] = $key.$sep_in.$value;
        }

        return implode($sep_out, $temp);
    }
    
    /** Получить список мест отдыха */
    public function get_resorts($params)
    {
        switch ($params['type']) {
            case 'objects': $sub_query_arr = $this->getObjCurrentFormSQL($params); break;
            case 'articles': $sub_query_arr = $this->getArtCurrentFormSQL($params); break;
            case 'events': $sub_query_arr = $this->getEventCurrentFormSQL($params); break;
            default: $sub_query_arr = null;
        }
        
        $count = '';
        if ($sub_query_arr) {
            $sub_query = $sub_query_arr['select'] . 'WHERE ' . $this->_getConditionSqlString($sub_query_arr['where'], array('resorts'));
            $count = $sub_query.' AND f.id=resort_id';
        }
        else {
            $count = 1;
        }
            
        $sql = 'SELECT f.*, ('.$count.') as count'
            .' FROM resorts f'
            .' ORDER BY f.name ASC';
        
        return $this->db->query($sql)->result_array();
    }    
    
    /**
     * Получить параметры для заполнения формы фильтров
     * @param array $params Выбранные параметры для формы фильтров
     * @param boolean $short_select 
     *      Флаг, необходим при ajax, чтобы уменьшить объем
     *      передаваемых данных для клиента
     * @return array Параметры формы
     */
    public function get_obj_form_params($params, $short_select = FALSE)
    {
        // Подзапрос
        $sub_query_arr = $this->getObjCurrentFormSQL($params);

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
        $sub_query = $sub_query_arr['select'] . 'WHERE ';
        foreach ($filters as $key => $val) {

            switch ($key) {
                case 'beachs':
                    $sub = $sub_query . $this->_getConditionSqlString($sub_query_arr['where'], array('beachs'));
                    $sql[] = '(SELECT '.$select.', ('.$sub.' AND f.url_name=o.'.$val['col'].') as count'
                        .' FROM obj_fields f'
                        .' WHERE f.field_id = '.$val['id'].')';                    
                    break;
                default:
                    $sub = $sub_query . $this->_getConditionSqlString($sub_query_arr['where']);
                    $sql[] = '(SELECT '.$select.', ('.$sub.' AND o.'.$val['col']." LIKE CONCAT(\"%\", f.url_name ,\"%\")) as count"
                        .' FROM obj_fields f'
                        .' WHERE f.field_id = '.$val['id'].')';  
            }
        }
        
        $fields = $this->db->query(implode(' UNION ', $sql))->result_array();
        
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
//        print'<pre>';print_r($data); die;
        return $data;
    }
    
    /**
     * Получить SQL-запрос, выводящий отели 
     * для текущего состояния формы фильтров 
     * @return string SQL запрос
     */
    public function getObjCurrentFormSQL($params)
    {
        $sqlArr = array();
        $sqlArr['select'] = 
            'SELECT count(1)'."\n"
            .' FROM objects o'."\n"
            .' JOIN resorts r ON r.id = o.resort_id'."\n"
            .' JOIN obj_fields b ON b.url_name = o.beach_id'."\n";
        $sqlArr['where'] = array(
            'common' => array('o.published = 1'),
        );
        
        // $select $where $where_custom

        // Наложение дополнительных условий на подзапрос
        foreach ($params as $key => $value) {

            if ( empty($params[$key]) )
                continue;

            switch ($key) {
                case 'beachs': case 'resorts':
                    $fields = array(
                        'resorts' => 'r',
                        'beachs' => 'b',
                    );
                    
                    $sqlArr['where'][$key] = $fields[$key].'.url_name IN ('.implode(',',array_map('escape', $value)).')';
                    break;

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
                        $sqlArr['where']['common'][] = "o.".$json_fields[$key]." LIKE '%".$this->db->escape_like_str($val)."%'";
                    break;

                case 'distance': case 'price_min': case 'price_max':
                    $border_fields = array(
                        'distance' => array('o.beach_distance', '<='),
                        'price_min' => array('o.price', '>='),
                        'price_max' => array('o.price', '<='),
                    );

                    $sqlArr['where']['common'][] = $border_fields[$key][0].' '.$border_fields[$key][1].' '.(int)$value;
                    break;
            }
        }
        
        return $sqlArr;
    }
    
    /**
     * Получить SQL-запрос, выводящий статьи 
     * для текущего состояния формы фильтров 
     * @return string SQL запрос
     */    
    public function getArtCurrentFormSQL($params)
    {
        $sqlArr = array();
        $sqlArr['select'] = 
            'SELECT count(1)'."\n"
            .' FROM articles a'."\n"
            .' JOIN resorts r ON r.id = a.resort_id'."\n";
        $sqlArr['where'] = array(
            'common' => array('a.status = 1'),
        );
        
        // Наложение дополнительных условий на подзапрос
        foreach ($params as $key => $value) {

            if ( empty($params[$key]) )
                continue;

            switch ($key) {
                case 'resorts':
                    $fields = array(
                        'resorts' => 'r',
                    );
                    
                    $sqlArr['where'][$key] = $fields[$key].'.url_name IN ('.implode(',',array_map('escape', $value)).')';
                    break;
            }
        }
        
        return $sqlArr;        
    }
    
    /**
     * Получить SQL-запрос, выводящий события 
     * для текущего состояния формы фильтров 
     * @return string SQL запрос
     */    
    public function getEventCurrentFormSQL($params)
    {
        $sqlArr = array();
        $sqlArr['select'] = 
            'SELECT count(1)'."\n"
            .' FROM events e'."\n"
            .' JOIN resorts r ON r.id = e.resort_id'."\n";
        $sqlArr['where'] = array(
            'common' => array('e.status = 1'),
        );
        
        // Наложение дополнительных условий на подзапрос
        foreach ($params as $key => $value) {

            if ( empty($params[$key]) )
                continue;

            switch ($key) {
                case 'resorts':
                    $fields = array(
                        'resorts' => 'r',
                    );
                    
                    $sqlArr['where'][$key] = $fields[$key].'.url_name IN ('.implode(',',array_map('escape', $value)).')';
                    break;
            }
        }
        
        return $sqlArr;        
    }
    
    /**
     * Получить из массива условий SQL строку для WHERE 
     * @param type $conditionArr Массив условия для sql запроса
     * @param type $exceptionFields Поля, условия для которых не включать в выборку
     * @return string
     */
    private function _getConditionSqlString($conditionArr, $exceptionFields = array())
    {
        $arr = array();
        
        foreach ($conditionArr as $cond => $values) {
            
            if (in_array($cond, $exceptionFields))
                continue;
            
            if (is_array($values))
            {
                foreach ($values as $val)
                    $arr[] = $val;
            }
            else
            {
                $arr[] = $values;
            }
        }

        return implode(' AND ', $arr);
    }
}