<?php

class MediaContent
{
    protected $stack;
    protected $limit;

    public function __construct($limit = 10) {
        // initialize the stack
        $this->stack = array();
        // stack can only contain this many items
        $this->limit = $limit;
    }

    public function push($item) {
        // trap for stack overflow
        if (count($this->stack) < $this->limit) {
            // prepend item to the start of the array
            array_unshift($this->stack, $item);
        } else {
            throw new RunTimeException('Stack is full!');
        }
    }

    public function pop() {
        if ($this->isEmpty()) {
            // trap for stack underflow
            throw new RunTimeException('Stack is empty!');
        } else {
            // pop item from the start of the array
            return array_shift($this->stack);
        }
    }

    public function top() {
        return current($this->stack);
    }

    public function isEmpty() {
        return empty($this->stack);
    }
}

class MediaData
{
    public $data;
    
    /*function __construct($jsonfile) {

        $aContext = array(
            'http' => array(
                'request_fulluri' => true,
            ),
        );
        $cxContext = stream_context_create($aContext);
        $sFile = file_get_contents($jsonfile, False, $cxContext);
        $data = json_decode($sFile, true);
        $this->data = $data;
    }*/

    function __construct($jsonfile) {
        $myfile = fopen($jsonfile, "r") or die("Unable to open file!");
        $data = json_decode(fread($myfile,filesize($jsonfile)), true);
        fclose($myfile);
        $this->data = $data;
    }

    public function readJsonData($jsonfile)
    {
        $myfile = fopen($jsonfile, "r") or die("Unable to open file!");
        $data = fread($myfile,filesize($jsonfile));
        fclose($myfile);
        
        return $data;
    }
    
    public function getMediaData()
    {
        $info = array();

        foreach ($this->data['data']['news'] as $value) {
            $obj = new stdClass();
            $obj->title = $value['title'];
            $obj->link = $value['link'];
            $obj->image = $value['image'];
            $obj->description = $value['description'];
            $info[] = $obj;
        }

        return $info;
    }
    
    
    public function getNumberOfData()
    {
        $count = 0;

        foreach ($this->data['data']['news'] as $value) {
            $count++;
        }

        return $count;
    }
    
    public function displayMediaData($index, $temp)
    {
        $number_of_data = $this->getNumberOfData();
        
        
        if($index > $number_of_data)
        {
            $maxindex = $number_of_data;
        }else
        {
            $maxindex = $index;
        }
        
        echo '<div class="row">';


        for($i=$index-3; $i < $maxindex; $i++) {

            if($temp[$i]->title != null)
            {
                echo '<div class="col-md-4 portfolio-item">';
                echo '<a href="#">';
                
                if($temp[$i]->image == null)
                {
                    echo '<img src="http://www.thenews.com.pk/assets/front/img/no-image.jpg" width=350 height="200"  alt="">';
                }
                else{
                    echo '<img src="'.$temp[$i]->image.'" width=350 height="200"  alt="">';
                }
                
                echo '</a>';
                echo '<h6>';
                echo '<a href="'.$temp[$i]->link.'">'.$temp[$i]->title.'</a><br>';
                echo '</h6>';
                echo $temp[$i]->description;
                echo '</div>';
            }

        }
        

        echo '</div>';
        echo '<hr style="color:black;" />';
        
    }
    
}

