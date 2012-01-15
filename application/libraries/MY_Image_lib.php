<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Image_lib extends CI_Image_lib
{
    
    function resize_and_crop($conf) {

        // Исходный файл
        $source = getimagesize($conf['source_image']);
        // Ширина и высота исходного файла
        $x = $source[0];
        $y = $source[1];

        // Определяем параметры для ресайза и кропа
        if ($x > $y)
        {
            $w = round( ($x/$y) * $conf['height'] );
            $h = $conf['height'];
            $x_axis = round(($w - $conf['width'])/2);
            $y_axis = 0;
        }
        else
        {
            $h = round( ($y/$x) * $conf['width'] );
            $w = $conf['width'];
            $y_axis = round(($h - $conf['height'])/2);
            $x_axis = 0;
        }

        /** Создание временного файла, для дальнейшего кропа */
        $temp_conf = $conf;
        $temp_conf['width'] = $w;
        $temp_conf['height'] = $h;
        $this->initialize($temp_conf);
        $this->resize();
        $this->clear();

        /** Делаем crop */
        $config = array();
        $config['source_image'] = $conf['new_image'];
        $config['maintain_ratio'] = FALSE;
        $config['width'] = $conf['width'];
        $config['height'] = $conf['height'];
        $config['x_axis'] = $x_axis;
        $config['y_axis'] = $y_axis;

        $this->initialize($config);
        $this->crop();
        $this->clear();

        return TRUE;
    }
}