<?php


class Db
{
	private $_numRow;
	private $dbh = null;

	public function __construct()
	{
		$driver = "mysql:host=" . HOST . "; dbname=" . DB_NAME;
		try {
			$this->dbh = new PDO($driver, DB_USER, DB_PASS);
			$this->dbh->query("set names 'utf8' ");
		} catch (PDOException $e) {
			echo 'có lỗi xảy ra';
			exit();
		}
	}

	public function __destruct()
	{
		$this->dbh = null;
	}

	public function getRowCount()
	{
		return $this->_numRow;
	}

	private function query($sql, $arr = array(), $mode = PDO::FETCH_ASSOC)
	{
		$stm = $this->dbh->prepare($sql);
		if (!$stm->execute($arr)) {
			echo "Sql lỗi.";
			exit;
		}
		$this->_numRow = $stm->rowCount();
		return $stm->fetchAll($mode);
	}

	public function select($sql,  $arr = array(), $mode = PDO::FETCH_ASSOC)
	{
		return $this->query($sql, $arr, $mode);
	}

	public function insert($sql,  $arr = array(), $mode = PDO::FETCH_ASSOC)
	{
		$this->query($sql, $arr, $mode);
		return $this->getRowCount();
	}

	public function update($sql,  $arr = array(), $mode = PDO::FETCH_ASSOC)
	{
		$this->query($sql, $arr, $mode);
		return $this->getRowCount();
	}

	public function delete($sql,  $arr = array(), $mode = PDO::FETCH_ASSOC)
	{
		$this->query($sql, $arr, $mode);
		return $this->getRowCount();
	}

	public function getInsertId()
	{
		return $this->dbh->lastInsertId();
	}

	public function pagination($current_page, $total_pages, $base_url)
	{
		echo '<div class="flex justify-center mt-5">';
		if ($current_page > 1) {
			echo '<a href="' . $base_url . '&page=' . ($current_page - 1) . '" class="px-3 py-2 mx-1 bg-gray-300 text-gray-700 rounded-lg">Trước</a>';
		}
		for ($i = 1; $i <= $total_pages; $i++) {
			if ($i == $current_page) {
				echo '<span class="px-3 py-2 mx-1 bg-white border border-black text-black rounded-lg">' . $i . '</span>';
			} else {
				echo '<a href="' . $base_url . '&page=' . $i . '" class="px-3 py-2 mx-1 bg-gray-300 text-gray-700 rounded-lg">' . $i . '</a>';
			}
		}
		if ($current_page < $total_pages) {
			echo '<a href="' . $base_url . '&page=' . ($current_page + 1) . '" class="px-3 py-2 mx-1 bg-gray-300 text-gray-700 rounded-lg">Sau</a>';
		}
		echo '</div>';
	}
}
