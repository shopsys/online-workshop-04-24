<?php

namespace SS6\ShopBundle\Command;

use SS6\ShopBundle\Component\Image\DirectoryStructureCreator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImageDirectoriesCommand extends ContainerAwareCommand {

	protected function configure() {
		$this
			->setName('ss6:image:directories')
			->setDescription('Create directories of images');
	}

	/**
	 * @param \Symfony\Component\Console\Input\InputInterface $input
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		$output->writeln('Start of creating directories for images.');

		$imageDirectoryStructureCreator = $this->getContainer()->get(DirectoryStructureCreator::class);
		/* @var $imageDirectoryStructureCreator \SS6\ShopBundle\Component\Image\DirectoryStructureCreator */
		$imageDirectoryStructureCreator->makeImageDirectories();

		$output->writeln('<fg=green>Directories of images was successfully created.</fg=green>');
	}

}
