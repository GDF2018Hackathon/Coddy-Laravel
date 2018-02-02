<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Log;

trait ProcessTrait
{
  /**
   * execute a terminal command
   *
   *  @return string result
   */
  public function exeCommand($commande)
  {
    $process = new Process($commande);

    try {
          $process->mustRun();
          Log::info('process success : '.$process->getOutput());
          echo $process->getOutput();
      } catch (ProcessFailedException $e) {
          echo $e->getMessage();
          Log::info('process error : '.$e->getMessage());
      }
  }

}
