<?php

echo "<!-- states -->\n";

class States
{
    private array $states = [
        123 => 'x',
        '12.4' => 'y',
        33 => 1,
        5 => 'y',
        6 => 'x',
        7 => 'y',
        8 => 'x',
    ];
    public function get(mixed $id)
    {
        $cl = '';
        if (isset($this->states[$id]))
        {
            // this is compatibility
            // former state was 1 for done
            // now it's:
            // x for done
            // y for postponed
            $v = $this->states[$id];
            $cl = $v == 1 ? 'x' : $v;
        }
        return $cl;
    }
}

$states = new States;
?>
