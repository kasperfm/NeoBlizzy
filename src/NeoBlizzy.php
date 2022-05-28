<?php
namespace KasperFM\NeoBlizzy;

class NeoBlizzy
{
    public function make(string $region = 'eu')
    {
        return new BNetApiHelper($region);
    }
}
