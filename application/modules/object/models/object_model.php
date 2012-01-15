<?php
class Object_Model extends CI_Model {
    
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
            ->select('o.*, u.username')
            ->from('objects o')
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

        $q = $this->db->get();

        return $q->result_array();
    }

    /** Получить список регионов */
    public function get_regions()
    {
        $this->db->select('*')
            ->from('regions')
            ->order_by('name', 'asc');

        $q = $this->db->get();

        return $q->result_array();
    }

    /** Получить список пунктов поля */
    public function get_field($field, $where = FALSE)
    {
        if (is_array($where) && !$where)
            return array();

        $prefix = 'obj_';

        $this->db->select('*')
            ->from($prefix.$field)
            ->order_by('name', 'asc');

        $this->db->where_in('url_name', $where);

        $q = $this->db->get();

        return $q->result_array();
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

        $this->db->select('o.*, r.name as resort, reg.name as region, t.name as type, b.name as beach')
            ->from('objects o')
            ->join('resorts r', 'r.id = o.resort_id')
            ->join('regions reg', 'reg.id = o.region_id')
            ->join('obj_types t', 't.id = o.type_id')
            ->join('obj_beachs b', 'b.id = o.beach_id')
            ->where($where);

        $q = $this->db->get();

        $data = $q->row_array();

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

        $this->db->insert_batch('objects', array($data));
    }

    /** Обновить отель */
    public function update($id, $data)
    {
        // Преобразование данных
        $data = $this->convert_data_to_save($data);

        // Сохранение метатегов
        if ( ! $this->metatags->update($data['meta_id']) )
            $data['meta_id'] = $this->metatags->create();

        $this->db->where('id', $id)
            ->update('objects', $data);
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