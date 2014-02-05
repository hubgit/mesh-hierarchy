<?php

if (!file_exists('mtrees2014.bin')) {
	exec('wget ftp://nlmpubs.nlm.nih.gov/online/mesh/.meshtrees/mtrees2014.bin -O mtrees2014.bin');
}

class Tree {
	protected $items = array();
	protected $data = array();
	protected $roots = array();

	public function read($file) {
		$input = fopen($file, 'r');

		while ($line = stream_get_line($input, null, "\n")) {
			list($name, $id) = explode(';', $line);

			$parts = explode('.', $id);
			$depth = count($parts);

			$item = array(
				'id' => $id,
				'name' => $name,
				'children' => array(),
			);

			if ($depth === 1) {
				$this->roots[] = $id;
			} else {
				$parent = implode('.', array_slice($parts, 0, -1));
				$this->items[$parent]['children'][] = $id;
			}

			$this->items[$id] = $item;
		}

		fclose($input);
	}

	public function build($id) {
		$this->data = array(
			'name' => 'mesh',
			'children' => array_map(array($this, 'build_node'), $this->roots),
		);
	}

	public function write($file) {
		file_put_contents($file, json_encode($this->data, JSON_PRETTY_PRINT));
	}

	protected function build_node($id) {
		$item = $this->items[$id];

		$node = array(
			'id' => $item['id'],
			'name' => $item['name'],
		);

		$children = array_map(array($this, 'build_node'), $item['children']);

		if ($children) {
			$node['children'] = $children;
		}

		return $node;
	}
}

$tree = new Tree;
$tree->read('mtrees2014.bin');
$tree->build('A01');
$tree->write('mtrees2014.json');

