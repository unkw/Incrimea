<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Upload extends CI_Upload
{
    function is_allowed_filetype()
    {
        if (count($this->allowed_types) == 0 OR ! is_array($this->allowed_types))
        {
            $this->set_error('upload_no_file_types');
            return FALSE;
        }
        $fType = strtolower(str_replace(".", "", $this->file_ext));
        if (in_array($fType, $this->allowed_types))
        {
            $mimes = $this->mimes_types($fType);
            if ( is_array($mimes) )
            {
				foreach( $mimes as $mime )
                {
					if ($this->file_type == $mime)
                    {
                        return TRUE;
                    }
                }
            }
            else
            {
                if ($this->file_type == $mimes)
                {
                    return TRUE;
                }
            }
        }
        return FALSE;
    } 
}