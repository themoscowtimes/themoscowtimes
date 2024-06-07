<?php

namespace Command;

use Sulfur\Console\Command;
use Sulfur\Data\Migration as DataMigration;
use Sulfur\Config;

class Migration extends Command
{

	protected $migration;

	protected $arguments = ['entity'];

	/**
	 * Creation command
	 * @param DataMigration $migration
	 * @param \Sulfur\Config $config
	 */
	public function __construct(DataMigration $migration, Config $config)
	{

		$this->migration = $migration;
		$this->config = $config;

	}


	/**
	 * Handle the command
	 */
	public function handle()
    {
		// Get the existing schema
		$old = $this->old();

		if($entity = $this->argument('entity')) {
			// Only update one entity
			$table = $entity::table();
			// start with old
			$new = $old;
			foreach($this->migration->schema([$entity]) as $database => $tables) {
				foreach($tables as $name => $columns) {
					$new[$database][$name] = $columns;
				}
			}
		} else {
			// Update all entities
			$new = $this->migration->schema();
		}

		$changes = $this->migration->diff($old, $new);

		// prepare for writing
		$time = date('YmdHis', time());
		$class = 'Sulfur' . substr(md5($time),0,8);

		// create a Phinx migration
		$contents = $this->phpMigration($new, $changes, $class);

		// write migration class
		$file = $this->config->phinx('paths.migrations') . '/' . $time . '_' . lcfirst($class) . '.php';
		file_put_contents($file, $contents);

		// write updated schema
		file_put_contents(
			$this->config->migration('schema') . $time . '_schema.json',
			json_encode($new, JSON_PRETTY_PRINT), FILE_APPEND
		);

		// write output
		$this->write([
			'Created Phinx migration file',
			'==============================',
			'File: ' . $time . '_' . lcfirst($class) . '.php',
			'At: ' . $this->config->phinx('paths.migrations'),
			'Check the file to make sure there are no unwanted drops or renames',
			'If everything is ok, run "phinx migrate" to send the changes to the database',
			'If it\'s not ok, change the migration file to correctly reflect the state of the entity files and then run "phinx migrate"'
		]);
    }


	/**
	 * Get the latest written schema
	 * @return array
	 */
	protected function old()
	{
		$path = $this->config->migration('schema');

		// get schema files
		$files = scandir($path, SCANDIR_SORT_DESCENDING);
		$files = array_values(array_filter($files, function($file) use ($path){
			return $file !== '.' && $file != '..' && !is_dir($path . $file);
		}));

		// get newest schema stored in a file
		if(count($files) > 0){
			$schema = json_decode(file_get_contents($path . $files[0]), true);
		} else {
			$schema = [];
		}
		return is_array($schema) ? $schema : [];
	}


	/**
	 * Create a Phinx migration
	 * @param array $schema
	 * @param array $changes
	 * @param string $class
	 * @return string
	 */
	protected function phpMigration($schema, array $changes, $class)
	{
		$php = [];

		foreach($changes as $database => $actions){

			$php[] = $this->phpDatabase($database);

			foreach($actions as $action => $tables) {
				switch($action){
					case 'rename':
						$php[] = $this->phpRenameTables($tables);
						break;
					case 'alter':
						foreach($tables as $table => $tableActions) {
							foreach($tableActions as $tableAction => $columns){

								switch($tableAction) {
									case 'rename':
										$php[] = $this->phpRenameColumns($table, $columns);
										break;
									case 'alter':
										$php[] = $this->phpAlterColumns($table, $columns, $schema[$database][$table]);
										break;
									case 'create':
										$php[] = $this->phpCreateColumns($table, $columns);
										break;
									case 'drop':
										$php[] = $this->phpDropColumns($table, $columns);
										break;
								}
							}
						}
						break;
					case 'create':
						$php[] = $this->phpCreateTables($tables);
						break;
					case 'drop':
						$php[] = $this->phpDropTables($tables);
						break;


				}
			}
		}
		return $this->phpClass($class, $this->format($php, 2));
	}




	/**
	 * Wrap changes in PHP class
	 * @param type $class
	 * @param type $change
	 * @return type
	 */
	protected function phpClass($class, $change)
	{
		$template = <<<'ID'
<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Table\Column;


class {{class}} extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
{{change}}
    }
}
ID;
		return str_replace(['{{class}}', '{{change}}'], [$class, $change], $template);
	}


	/**
	 * Use a certain database
	 * @param string $name
	 * @return array
	 */
	protected function phpDatabase($name)
	{
		return [
			'// USE DATABASE \'' . $name . '\'',
			'$config = Phinx\Config\Config::fromPhp(__DIR__ . \'/../../config/phinx.php\');',
			'$environment = new Phinx\Migration\Manager\Environment(\'' . $name . '\', $config->getEnvironment(\'' . $name . '\'));',
			'$adapter = $environment->getAdapter();',
			'$this->setAdapter($adapter);',
			"\n",
		];
	}


	/**
	 * Create tables block
	 * @param array $tables
	 * @return array
	 */
	protected function phpCreateTables(array $tables)
	{
		$php = ['// CREATE TABLES'];
		foreach($tables as $table => $columns) {
			$php[] = $this->phpCreateColumns($table, $columns);
		}
		return $php;
	}


	/**
	 * Drop tables block
	 * @param array $tables
	 * @return array
	 */
	protected function phpDropTables(array $tables)
	{
		$php = ['// DROP TABLES'];
		foreach($tables as $table ) {
			$php[] = '$this->table(\'' . $table . '\')->drop()->save();';
		}
		$php[] = "\n";
		return $php;
	}


	/**
	 * Rename tables block
	 * @param array $tables
	 * @return array
	 */
	protected function phpRenameTables(array $tables)
	{
		$php = ['// RENAME TABLES'];
		foreach($tables as $table =>$name) {
			$php[] = '$this->table(\'' . $table . '\')';
			$php[] = '->rename(\'' . $name . '\');';
		}
		$php[] = "\n";
		return $php;
	}


	/**
	 * Create columns block
	 * @param string $table
	 * @param array $columns
	 * @return array
	 */
	protected function phpCreateColumns($table, array $columns)
	{
		$php = ['// CREATE COLUMNS'];
		$php[] = '$this->table(\'' . $table . '\')';
		foreach($columns as $column => $properties) {
			if($column !== 'id') {

				// set limit to get mediumtext and tinyint
				if($limit = $this->phinxLimit($properties['type'])) {
					$properties['limit'] = $limit;
				}

				$php[] = [
					'->addColumn(',
					['\'' . $column . '\',', 1],
					['\'' . $this->phinxType($properties['type']) . '\', ', 1],
					[$this->phinxProperties($properties), 1],
					')'
				];
				$php[] = $this->phpCreateIndex($column, $properties);
			}
		}
		$php[] = '->save();';
		$php[] = "\n";

		return $php;
	}


	/**
	 * Drop columns block
	 * @param string $table
	 * @param array $columns
	 * @return array
	 */
	protected function phpDropColumns($table, array $columns)
	{
		$php = ['// DROP COLUMS'];
		$php[] = '$this->table(\'' . $table . '\')';
		foreach($columns as $column) {
			$php[] = '->removeColumn(\'' . $column . '\')';
		}
		$php[] = '->save();';
		$php[] = "\n";

		return $php;
	}


	/**
	 * Rename columns block
	 * @param type $table
	 * @param type $columns
	 * @return string
	 */
	protected function phpRenameColumns($table, array $columns)
	{
		$php = ['// RENAME COLUMS'];
		foreach($columns as $column => $name) {
			$php[] = '$this->table(\'' . $table . '\')';
			$php[] = '->renameColumn(\'' . $column . '\', \'' . $name . '\')';
			$php[] = '->save();';
		}
		$php[] = "\n";
		return $php;
	}


	/**
	 * Alter columns block
	 * @param string $table
	 * @param array $columns
	 * @param array $schema
	 * @return string
	 */
	protected function phpAlterColumns($table, array $columns, array $schema)
	{
		$php = ['// CHANGE COLUMS'];
		$php[] = '$this->table(\'' . $table . '\')';
		foreach($columns as $column => $properties) {
			if(! isset($properties['type'])) {
				// phinx needs type: if not altered, get it from the new schema
				$properties['type'] = $schema[$column]['type'];
			}

			// set limit to get mediumtext and tinyint
			if($limit = $this->phinxLimit($properties['type'])) {
				$properties['limit'] = $limit;
			}
			$php[] = [
				'->changeColumn(',
				['\'' . $column . '\',', 1],
				['\'' . $this->phinxType($properties['type']) . '\', ', 1],
				[$this->phinxProperties($properties, $schema[$column]), 1],
				')'
			];
			$php[] = $this->phpAlterIndex($column, $properties);
		}
		$php[] = '->save();';
		$php[] = "\n";

		return $php;
	}


	/**
	 * Convert sulfur types to Phinx types
	 * @param string $type
	 * @return string
	 */
	protected function phinxType($type)
	{
		if($type === 'int') {
			return 'integer';
		}
		if($type === 'bool') {
			return 'boolean';
		}
		if($type === 'json') {
			return 'text';
		}
		return $type;
	}


	protected function phinxLimit($type)
	{
		if($type === 'json') {
			return 'TEXT_MEDIUM';
		}
	}

	/**
	 * Extract Phinx properties from Sulfur properties
	 * @param array $properties
	 * @param array $schema
	 * @return string
	 */
	protected function phinxProperties(array $properties, $schema = [])
	{
		$props = [];
		foreach(['length', 'values', 'null', 'default', 'after', 'increment', 'limit'] as $prop) {
			if(isset($properties[$prop])){
				$val = $properties[$prop];
			} elseif(isset($schema[$prop])) {
				// explicitely set the unchanged values too when altering, otherwise mysql will loose stuff, for instance default values
				$val = $schema[$prop];
			} else {
				$val = '___none___';
			}

			if($val !== '___none___') {
				if($prop == 'increment') {
					$props['identity'] = $val;
				} else {
					$props[$prop] = $val;
				}
			}
		}
		return var_export($props, true);
	}


	/**
	 * Create or remove indexes
	 * @param string $column
	 * @param array $properties
	 * @return string
	 */
	protected function phpAlterIndex($column, array $properties)
	{

		if(isset($properties['index']) && $properties['index']){
			return $this->phpCreateIndex($column, $properties);
		} elseif(isset($properties['index']) && !$properties['index']) {
			return '->removeIndex(\'' . $column .'\')';
		}
	}


	/**
	 * Create an index
	 * @param string $column
	 * @param array $properties
	 * @return boolean|string
	 */
	protected function phpCreateIndex($column, array $properties)
	{
		if($properties['index']){
			if(isset($properties['unique']) && $properties['unique']){
				$unique = ', \'unique\' => true';
			} else {
				$unique = '';
			}
			return '->addIndex(\'' . $column .'\', [\'name\' => \'' . $column . '\'' . $unique. '])';
		}
		return false;
	}


	/**
	 * Format lines into nicely indented code
	 * @param array $lines
	 * @param int $indent
	 * @return string
	 */
	protected function format(array $lines, $indent = 0)
	{
		$result = [];
		foreach($lines as $line){
			$formattedLine = false;
			if(is_string($line)){
				if($line === "\n" || $line === 'newline') {
					// a specific newline, just add an empty line
					$formattedLine = '';
				} else {
					$parts = explode("\n", $line);
					if(count($parts) > 1){
						// multiline string, format with indent
						$formattedLine = $this->format($parts, $indent);
					} else {
						// one line: just indent
						$formattedLine = $this->indent($line, $indent);
					}
				}
			} elseif(is_array($line)) {

				if(count($line) == 2 && is_int($line[1]) ) {
					// line or lines with extra indent [$line, $indent]
					$formattedLine = $this->format([$line[0]], $line[1] + $indent);
				} elseif(count($line) > 0) {
					// multiple lines
					$formattedLine = $this->format($line, $indent);
				}
			}
			// add formetted line
			if($formattedLine !== false){
				$result[] = $formattedLine;
			}
		}
		// separate all lines by newline
		return implode("\n", $result);
	}


	/**
	 * prefix a string with a number of tabs
	 * @param string $line
	 * @param int $amount
	 * @return string
	 */
	protected function indent($line, $amount)
	{
		$tabs = '';
		for($i = 0; $i < $amount; $i++){
			$tabs .= "\t";
		}
		return $tabs . $line;
	}
}