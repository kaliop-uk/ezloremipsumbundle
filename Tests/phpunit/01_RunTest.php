<?php

include_once(__DIR__.'/CommandExecutingTest.php');

class RunTest extends CommandExecutingTest
{
    /**
     * @dataProvider provideMigrationList
     */
    public function testMigrationExecution($filePath)
    {
        $out = $this->runMigration($filePath);
    }

    public function provideMigrationList()
    {
        $dslDir = $this->dslDir.'/good';
        if (!is_dir($dslDir)) {
            return array();
        }

        $out = array();
        foreach(scandir($dslDir) as $fileName) {
            $filePath = $dslDir . '/' . $fileName;
            if (is_file($filePath)) {
                $out[] = array($filePath);
            }
        }
        return $out;
    }

    protected function runMigration($path, array $params = array())
    {
        /// @todo should we first remove the migration if it was already run?
        $params = array_merge($params, array('--path' => array($path), '-n' => true, '-f' => true, '-u' => true));
        $out = $this->runCommand('kaliop:migration:migrate', $params);
        // check that there are no notes related to adding the migration before execution
        //$this->assertNotContains('Skipping ' . basename($path), $out, "Migration definition is incorrect?");
        $this->assertRegexp('?\| ' . basename($path) . ' +\| +\|?', $out);
        return $out;
    }
}
