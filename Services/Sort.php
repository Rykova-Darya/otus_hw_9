<?php

namespace Services;

class Sort
{
    private int $size;
    private int $maxValue;
    private array $arr = [];

    private int $ms;
    private int $cmp;
    private int $asg;

    private array $sortedArr = [];
    public function __construct($size = 100, $maxValue = 999)
    {
        $this->size = $size;
        $this->maxValue = $maxValue;
        $this->ms = 0;
        $this->asg = 0;
        $this->cmp = 0;
    }

    public function setRandomArray($size)
    {
        $this->size = $size;
        $this->arr = array_map(fn() => rand(0, $this->maxValue), range(1, $this->size));
    }

    public function countingSort()
    {
        $start = (int) (microtime(true) * 1000);
        $this->sortedArr = [];
        $this->cmp = 0;
        $this->asg = 0;

        $countArr = array_fill(0, $this->maxValue + 1, 0);

        foreach ($this->arr as $value)
        {
            $countArr[$value]++;
            $this->cmp++;
        }

        foreach ($countArr as $value => $frequency) {
            while ($frequency-- > 0) {
                $this->sortedArr[] = $value;
                $this->asg++;
            }
        }

        $this->ms = (int) (microtime(true) * 1000) - $start;

    }

    public function radixSort()
    {
        $start = (int) (microtime(true) * 1000);
        $this->sortedArr = $this->arr;
        $this->cmp = 0;
        $this->asg = 0;

        $place = 1;
        while ($this->maxValue / $place > 1) {
            $this->sortedArr = $this->countingSortForRadix($this->sortedArr,$place);
            $place *= 10;
        }
        $this->ms = (int) (microtime(true) * 1000) - $start;
    }

    public function bucketSort($bucketCount = 10)
    {
        $start = (int) (microtime(true) * 1000);
        $this->sortedArr = [];
        $this->cmp = 0;
        $this->asg = 0;
        $buckets = array_fill(0, $bucketCount, []);

        foreach ($this->arr as $num) {
            $bucketIndex = (int) ($num / ($this->maxValue + 1) * $bucketCount);
            $buckets[$bucketIndex] = $this->insertSorted($buckets[$bucketIndex], $num);
        }
        foreach ($buckets as $bucket) {
            foreach ($bucket as $num) {
                $this->sortedArr[] = $num;
                $this->asg++;
            }
        }
        $this->ms = (int) (microtime(true) * 1000) - $start;
    }

    public function toString()
    {
        echo "Длинна массива: " . $this->size . "\tms: " . $this->ms .
        "\tСравнений (cmp): " . $this->cmp .
        "\tОбменов (asg): " . $this->asg . PHP_EOL;
    }

    private function countingSortForRadix($arr, $place)
    {
        $output = array_fill(0, $this->size, 0);
        $count = array_fill(0, 10, 0);

        foreach ($arr as $num) {
            $digit = intdiv($num, $place) % 10;
            $count[$digit]++;
            $this->cmp++;
        }

        for ($i = 1; $i < 10; $i++) {
            $count[$i] +=$count[$i - 1];
        }

        for ($i = $this->size - 1; $i >= 0; $i--) {
            $digit = intdiv($arr[$i], $place) % 10;
            $output[$count[$digit] - 1] = $arr[$i];
            $count[$digit]--;
            $this->asg++;
        }

        return $output;
    }

    private function insertSorted($bucket, $num)
    {
        $size = count($bucket);

        if ($size === 0) {
            $bucket[] = $num;
            $this->asg++;
            return $bucket;
        }

        for ($i = $size - 1; $i >= 0; $i--) {
            $this->cmp++;
            if ($bucket[$i] > $num) {
                $bucket[$i + 1] = $bucket[$i];
                $this->asg++;
            } else {
                $bucket[$i + 1] = $num;
                $this->asg++;
                return $bucket;
            }
        }

        // Если элемент оказался самым маленьким
        $bucket[0] = $num;
        $this->asg++;
        return $bucket;
    }
}