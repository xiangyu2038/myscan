<?php

namespace App\Helper;



use Illuminate\Support\Facades\Facade;


class ExcelHelper extends Facade
{
    /**
     * 继承自父类抽象方法
     * @param
     * @return mixed
     */
    protected static function getFacadeAccessor() {
        return ExcelHelper::class;
    }
    
    /**
     * 引入excel 文件 导出
     * @param 
     * @return mixed
     */
    protected function includeExcelExport(){
        include_once base_path().'/lib/PHPExcel/PHPExcel.class.php';
        return  new \PHPExcel();
    }

    /**
     * 引入excel 文件导入
     * @param
     * @return mixed
     */
    protected function includeExcelImport($ext='xls'){

        include_once base_path().'/lib/PHPExcel/PHPExcel.class.php';

        //如果excel文件后缀名为.xls，导入这个类
        if ($ext == 'xls') {
            include_once base_path().'/lib/PHPExcel/PHPExcel/Reader/Excel5.php';
           return  new \PHPExcel_Reader_Excel5();
        } else if ($ext == 'xlsx') {
            include_once base_path().'/lib/PHPExcel/PHPExcel/Reader/Excel2007.php';
            return  new \PHPExcel_Reader_Excel2007();
        }
    }



    /////////////////////////////////////////////////////////////////////////////////////
    /// /////////////////////////////////////////////////////////////////////////////////
    protected function exports($data,$headArr,$title='导出表格',$sheet_title=['出库数据汇总'],$php_eol=['F'],$width=[['F','60']]){
        $objPHPExcel = $this->includeExcelExport();
        foreach ($data as $key=>$v){
            $titles=$sheet_title[$key];
            $this->sheet($v,$objPHPExcel,$headArr,$titles,$php_eol,$width,$key);
        }
        $fileName = iconv("utf-8", "gb2312", $title . date('Ymd') . '.xls');
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载
    }
    protected function sheet($data,&$objPHPExcel,$headArr,$title='测试title',$php_eol=['F'],$width=[['F','60']],$keys){
        $objPHPExcel->getProperties(); //获取属性
        $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex($keys);

        $objActSheet = $objPHPExcel->getActiveSheet()->setTitle($title);

//$objPHPExcel->getActiveSheet()->mergeCells('A1:B1');//合并单元格

        $objActSheet->getDefaultStyle()->getFont()->setSize(10);
        /* $objActSheet->getStyle('A:U')->getAlignment()->applyFromArray(array('horizontal' => '', 'vertical' => 'center', 'rotation' => 0, 'wrap' => TRUE));
         $objActSheet->getStyle('A1:U1')->getAlignment()->applyFromArray(array('horizontal' => 'center', 'vertical' => 'center', 'rotation' => 0, 'wrap' => TRUE));*/
// $objActSheet->getRowDimension('1')->setRowHeight(22);
//背景色
        /* $objActSheet->getStyle('A1:U1')->getFill()->getStartColor()->setRGB('f9ce19');
         $objActSheet->getStyle('A1:U1')->getFill()->setFillType('solid');*/
//$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        /* $objActSheet->getColumnDimension('A')->setAutoSize (true);
        $objActSheet->getColumnDimension('B')->setAutoSize (true);
        $objActSheet->getColumnDimension('C')->setAutoSize (true);
        $objActSheet->getColumnDimension('D')->setAutoSize (true);
        $objActSheet->getColumnDimension('E')->setAutoSize (true);*/

        $objPHPExcel -> getActiveSheet() -> getColumnDimension(\PHPExcel_Cell::stringFromColumnIndex(1)) -> setAutoSize(true);///设置自动宽度

        /*$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setWrapText(true);*/
        foreach ($php_eol as $v){
            $objPHPExcel->getActiveSheet()->getStyle($v)->getAlignment()->setWrapText(true);
        }


        /* $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setWrapText(true);
         $objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->setWrapText(true);*/
//$objPHPExcel->getActiveSheet()->mergeCells('A1:A2');
//$objPHPExcel->getActiveSheet()->unmergeCells('A1:A2');
        foreach ($width as $v){
            $objActSheet->getColumnDimension($v[0])->setWidth($v[1]);
        }

        // $objPHPExcel->setactivesheetindex($key);

        $key=0;
        foreach($headArr as  $vv){
            //注意，不能少了。将列数字转换为字母\
            $colum = \PHPExcel_Cell::stringFromColumnIndex($key);
            $objPHPExcel->setActiveSheetIndex($keys) ->setCellValue($colum.'1', $vv);
            $key += 1;
        }
        $column = 2; //从第二行写入数据 第一行是表头

        foreach($data as $key => $rows){ //行写入
            //dd($rows);
            $span = 0;
            foreach($rows as $keyName=>$value){
                // 列写入
                $j = \PHPExcel_Cell::stringFromColumnIndex($span);

                $objActSheet->setCellValue($j.$column, $value);
                $span++;
            }
            $column++;
        }

        //  return $objPHPExcel;
    }



//////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////



/////////////////////////////////////////////////////////////////////////////////////
    /// /////////////////////////////////////////////////////////////////////////////////

/**
 * 导入程序
 * @param 
 * @return *表格数据
 */    
protected function import($filename,$ext = 'xls'){
    $PHPReader=$this->includeExcelImport($ext);
    $PHPExcel = $PHPReader->load($filename);

    //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
    $currentSheet = $PHPExcel->getSheet(0);
    //获取总列数
    $allColumn = $currentSheet->getHighestColumn();
    ++$allColumn;
    $data=[];///接受数组   所有的导入的数据
    //获取总行数
    $allRow = $currentSheet->getHighestRow();
    ++$allColumn;
    //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
    for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {
        //从哪列开始，A表示第一列
        for ($currentColumn = 'A'; $currentColumn != $allColumn; $currentColumn++) {
            //数据坐标
            $address = $currentColumn . $currentRow;
            //读取到的数据，保存到数组$arr中
            $data[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
        }
    }
    return $data;
}

    /////////////////////////////////////////////////////////////////////////////////////
    /// /////////////////////////////////////////////////////////////////////////////////

}


