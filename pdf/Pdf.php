<?php

namespace atom\pdf;

require_once(__DIR__ . '/tfpdf/tfpdf.php');

use yii\helpers\ArrayHelper;

class Pdf extends \tFPDF
{
    private function WriteHeaderRow($columns)
    {
        $this->WriteRow($columns, array_map(fn ($row) => $row[0], $columns), false);
    }

    private function WriteRow($columns, $row, $checkPageBreak = true)
    {
        $nb = 0;
        foreach ($columns as $key => $column) {
            $nb = max($nb, $this->NbLines($column['w'], ArrayHelper::getValue($row, $key, '')));
        }
        $h = $nb * 5;

        if ($checkPageBreak) {
            if ($this->GetY() + $h > $this->PageBreakTrigger) {
                $this->AddPage($this->CurOrientation);
                $this->writeHeaderRow($columns);
            }
        }

        foreach ($columns as $key => $column) {
            $w = $column['w'];
            $x = $this->GetX();
            $y = $this->GetY();
            $this->Rect($x, $y, $w, $h);
            $this->MultiCell($w, 5, ArrayHelper::getValue($row, $key, ''), 0, ArrayHelper::getValue($column, 'a', 'L'));
            $this->SetXY($x + $w, $y);
        }
        $this->Ln($h);
    }

    private function NbLines($w, $txt)
    {
        $wmax = ($w - 2 * $this->cMargin);
        $nb = 1;
        $words = explode(' ', $txt);
        $s = array_shift($words);
        while ($words) {
            $word = array_shift($words);
            $s .= ' ' . $word;
            if ($this->GetStringWidth($s) > $wmax) {
                $nb++;
                $s = $word;
            }
        }
        return $nb;
    }

    public function WriteTable($columns, $data)
    {
        $this->WriteHeaderRow($columns);
        foreach ($data as $row) {
            $this->WriteRow($columns, $row);
        }
    }
}
