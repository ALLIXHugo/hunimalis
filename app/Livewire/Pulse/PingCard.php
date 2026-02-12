<?php
namespace App\Livewire\Pulse;
use Laravel\Pulse\Livewire\Card;
use Livewire\Component;

class PingCard extends Card
{
    public string $host = '51.83.36.122';
    public int $port = 2408;
    
    public string $pingTime = '-'; 
    public bool $isOnline = false;

    public function mount()
    {
        $this->checkPing();
    }

    public function checkPing()
    {
        $start = microtime(true);
        $connection = @fsockopen($this->host, $this->port, $errno, $errstr, 1); 
        $end = microtime(true);
        if ($connection) {
            fclose($connection);
            $duration = ($end - $start) * 1000; 
            $this->pingTime = number_format($duration, 3); 
            $this->isOnline = true;
        } else {
            $this->pingTime = 'Down';
            $this->isOnline = false;
        }
    }

    public function render()
    {
        return view('livewire.pulse.ping-card');
    }
}