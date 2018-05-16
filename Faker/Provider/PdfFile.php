<?php

namespace Kaliop\eZLoremIpsumBundle\Faker\Provider;

use TCPDF;

class PdfFile extends Base
{
    public function pdfFile($dir = null, $pages=5, $title = '', $author = '', $subject = '', $keywords = '')
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->getAuthor($author));
        $pdf->SetTitle($this->getTitle($title));
        $pdf->SetSubject($this->getSubject($subject));
        $pdf->SetKeywords($this->getKeywords($keywords));

        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        /*if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }*/

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 14, '', true);

        $pages = mt_rand(1, $pages);

        for ($i = 0; $i < $pages; $i++) {
            $pdf->AddPage();
            $html = $this->getPageHtml($i);
            $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        }

        // Close and save PDF document
        $fileName = $this->getFilename($dir);

        $pdf->Output($fileName, 'F');

        return $fileName;
    }

    protected function getAuthor($author = '')
    {
        if ($author !== '') {
            return $author;
        }

        return $this->generator->name;
    }

    protected function getTitle($title = '')
    {
        if ($title !== '') {
            return $title;
        }

        return $this->generator->sentence;
    }

    protected function getSubject($subject = '')
    {
        if ($subject !== '') {
            return $subject;
        }

        return $this->generator->paragraph;
    }

    protected function getKeywords($keywords = '')
    {
        if ($keywords !== '') {
            return $keywords;
        }

        return implode(', ', $this->generator->words);
    }

    protected function getPageHtml($pageNum)
    {
        return $this->generator->randomHtml();
    }

    protected function getFileName($dir = null)
    {
        $dir = is_null($dir) ? sys_get_temp_dir() : $dir; // GNU/Linux / OS X / Windows compatible
        // Validate directory path
        if (!is_dir($dir) || !is_writable($dir)) {
            throw new \InvalidArgumentException(sprintf('Cannot write to directory "%s"', $dir));
        }

        // Generate a random filename. Use the server address so that a file
        // generated at the same time on a different server won't have a collision.
        $name = md5(uniqid(empty($_SERVER['SERVER_ADDR']) ? '' : $_SERVER['SERVER_ADDR'], true));
        $filename = $name .'.pdf';
        $filepath = $dir . DIRECTORY_SEPARATOR . $filename;

        return $filepath;
    }
}
