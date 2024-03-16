<?php

namespace WordPress\Blueprints\Model\DataClass;

class MvStep implements StepDefinitionInterface
{
	const DISCRIMINATOR = 'mv';

	/** @var Progress */
	public $progress;

	/** @var bool */
	public $continueOnError = false;

	/** @var string */
	public $step = 'mv';

	/**
	 * Source path
	 * @var string
	 */
	public $fromPath = null;

	/**
	 * Target path
	 * @var string
	 */
	public $toPath = null;


	public function setProgress(Progress $progress)
	{
		$this->progress = $progress;
		return $this;
	}


	public function setContinueOnError(bool $continueOnError)
	{
		$this->continueOnError = $continueOnError;
		return $this;
	}


	public function setStep(string $step)
	{
		$this->step = $step;
		return $this;
	}


	public function setFromPath(string $fromPath)
	{
		$this->fromPath = $fromPath;
		return $this;
	}


	public function setToPath(string $toPath)
	{
		$this->toPath = $toPath;
		return $this;
	}
}
