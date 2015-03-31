<?php
require_once 'vendor/autoload.php';

use Symfony\Component\Console\Application;

class Instances
{
	private $data = [];

	public function addInstance($instance)
	{
		$this->_init($instance['Placement']['AvailabilityZone'], $instance['InstanceType']);
		$this->data[$instance['Placement']['AvailabilityZone']][$instance['InstanceType']]['total'] ++;

	}

	public function addReservedInstance($reservedInstance)
	{
		$this->_init($reservedInstance['AvailabilityZone'], $reservedInstance['InstanceType']);
		$this->data[$reservedInstance['AvailabilityZone']][$reservedInstance['InstanceType']]['reserved'] += $reservedInstance['InstanceCount'];
	}

	public function getData()
	{
		return $this->data;
	}

	private function _init($az, $instanceType)
	{
		if (!isset($this->data[$az])) {
			$this->data[$az] = [];
		}

		if (!isset($this->data[$az][$instanceType])) {
			$this->data[$az][$instanceType] = [
				'total' => 0,
				'reserved' => 0,
			];
		}
	}

}

class InstancesCommand extends \Symfony\Component\Console\Command\Command
{

	protected function configure()
	{
		$this
			->setName('instances')
			->setDescription('Instanes Reservations Table')
			->addArgument(
				'region',
				\Symfony\Component\Console\Input\InputArgument::OPTIONAL,
				'Who do you want to greet?',
				'us-east-1'

			)
		;
	}

	protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output)
	{

		$client = \Aws\Ec2\Ec2Client::factory([
			'region' => $input->getArgument('region'),
		]);

		$instances = new Instances();

		foreach ($client->describeInstances(['Filters' => [['Name' => 'instance-state-name', 'Values' => ['running']]]])->get('Reservations') as $reservation) {
			foreach ($reservation['Instances'] as $instance) {
				$instances->addInstance($instance);
			}
		}

		foreach ($client->describeReservedInstances(['Filters' => [['Name' => 'state', 'Values' => ['active']]]])->get('ReservedInstances') as $reservedInstance) {
			$instances->addReservedInstance($reservedInstance);
		}

		$table = new \Symfony\Component\Console\Helper\Table($output);
		$table->setHeaders(['Zone', 'Instance', 'Total', 'Reserved', 'Reserve', 'Sell']);
		$rows = [];
		foreach ($instances->getData() as $az => $azInstances) {
			foreach ($azInstances as $instanceType => $metrics) {
				$rows[] = [
					'az' => $az,
					'instanceType' => $instanceType,
					'total' => $metrics['total'],
					'reserved' => $metrics['reserved'],
					'reserve' => max(0, $metrics['total'] - $metrics['reserved']),
					'sell' => max(0, $metrics['reserved'] - $metrics['total']),
				];
			}
		}

		usort($rows, function($row1, $row2) {
			return strcmp($row1['instanceType'], $row2['instanceType']);
		});

		$table->setRows($rows);
		$table->render();
	}

}

$app = new Application();
$app->add(new InstancesCommand());
$app->run();

