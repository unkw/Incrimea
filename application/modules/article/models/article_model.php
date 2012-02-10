<?php
class Article_Model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }

    public function get_count_all()
    {
        return $this->db->count_all('articles');
    }

    /** Получить список страниц */
    public function get_pages($num, $offset)
    {
        $this->db
            ->select('a.*, al.alias, u.username')
            ->from('articles a')
            ->join('alias al', 'al.id = a.alias_id', 'left')
            ->join('users u', 'u.id = a.uid')
            ->order_by('a.created_date', 'desc')
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

    /** Получить страницу */
    public function get($id, $published = FALSE)
    {
        $where = array('id' => $id);

        if ($published)
            $where['status'] = 1;

        $q = $this->db->get_where('articles', $where);

        return $q->row_array();
    }
    
    /** Добавить страницу */
    public function add($data)
    {
        // Сохранение метатегов
        $data['meta_id'] = $this->metatags->create();    

        $this->db->insert('articles', $data);

        // Сохранение алиаса
        $this->save_path($this->db->insert_id(), $data, TRUE);
    }

    /** Обновить страницу */
    public function update($id, $data)
    {
        // Сохранение метатегов
        if ( ! $this->metatags->update($data['meta_id']) )
            $data['meta_id'] = $this->metatags->create();

        // Сохранение синонима
        $this->save_path($id, & $data);
        
        $this->db->update('articles', $data, array('id' => $id));
    }

    /** Сохранение синонима */
    private function save_path($id, & $data, $create = FALSE)
    {
        $pathdata = array(
            'realpath' => 'article/view/'.$id,
            'auto'     => $this->input->post('pathauto') ? 1 : 0,
        );

        // Формируем алиас
        $resort = $this->db->get_where('resorts', array('id' => $data['resort_id']))->row_array();
        if (!$pathdata['auto'] && trim($this->input->post('path')))
            $pathdata['alias'] = $this->input->post('path');
        else
            $pathdata['alias'] = 'article/'.$resort['name'].'/'.$data['title'];

        // Сохранение
        if ( $create || !$data['alias_id'] || !$this->path->update($pathdata, $data['alias_id']) )
            $data['alias_id'] = $this->path->create($pathdata);

        // Обновить alias_id контента при создании контента
        if ($create)
            $this->db->update('articles', array('alias_id'=>$data['alias_id']), array('id'=>$id));

        return TRUE;
    }

    /** Ресайз изображения и создание его превьюшки */
    public function create_images($img)
    {
        // Основные параметры
        $default_config = $this->config->config['image_lib'];
        // Оригинальный файл
        $origin_img = 'images/article/' . $img['file_name'];

        /** Создание основного изображения */
        $size = $this->config->config['image_main'];
        $large_img = $origin_img;
        $config = array();
        $config = array_merge($default_config, $size);
        $config['source_image'] = $large_img;

        $this->load->library('image_lib', $config);
        $this->image_lib->resize();
        $this->image_lib->clear();

        /** Создание превью изображения */
        $size = $this->config->config['thumb'];
        $thumb = 'images/article/thumb/' . $img['file_name'];
        $config = array();
        $config = array_merge($default_config, $size);
        $config['source_image'] = $large_img;
        $config['new_image'] = $thumb;

        $this->image_lib->resize_and_crop($config);
    }

}