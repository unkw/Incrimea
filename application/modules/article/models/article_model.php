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

        return $this->db->get()->result_array();
    }

    /** Получить список всех курортов */
    public function get_resorts()
    {
        $this->db->select('*')
            ->from('resorts')
            ->order_by('name', 'asc');

        return $this->db->get()->result_array();
    }

    /** Получить страницу */
    public function get($id, $published = FALSE)
    {
        $where = array('id' => $id);

        if ($published)
            $where['status'] = 1;

        return $this->db->get_where('articles', $where)->row_array();
    }
    
    /** Добавить статью */
    public function add($data)
    {
        // Сохранение метатегов
        $data['meta_id'] = $this->metatags->create();    
        
        // Создание статьи
        $this->db->insert('articles', $data);
        $art_id = $this->db->insert_id();

        // Сохранение синонима
        $path_data = $this->generate_path($art_id, $data);
        $alias_id = $this->path->create($path_data);
        $this->db->update('articles', array('alias_id' => $alias_id), array('id' => $art_id));
    }

    /** Обновить статью */
    public function update($id, $data)
    {
        // Сохранение метатегов
        $this->metatags->update($data['meta_id']);

        // Сохранение синонима
        $path_data = $this->generate_path($id, $data);
        $this->path->update($data['alias_id'], $path_data);
        
        $this->db->update('articles', $data, array('id' => $id));
    }

    /**
     * Генерация url синонима
     * @param int $obj_id - ID
     * @param array $data - данные
     * @return array
     */
    public function generate_path($obj_id, $data)
    {
        $pathdata = array(
            'realpath' => 'article/view/'.$obj_id,
            'auto' => $this->input->post('pathauto') ? 1 : 0,
        );
        
        if ($pathdata['auto'])
        {
            $resort = $this->db->get_where('resorts', array('id' => $data['resort_id']))->row_array();
            $pathdata['alias'] = array('article', $resort['name'], $data['title']);
        }
        else
        {
            $pathdata['alias'] = array($this->input->post('path'));
        }
        
        return $pathdata;
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