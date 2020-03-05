<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Cache_Memcacheimp_Driver — усовершенствованный в части работы с тэгами драйвер для Memcached
 *
 * @author Kolger
 * Реализует метод работы с тэгами, описанный на странице http://www.smira.ru/2008/10/29/web-caching-memcached-5/
 */
class Kohana_Cache_Memcacheimp extends Cache implements Cache_Arithmetic {

	protected $backend;
	protected $flags;
        protected $_default_config = array();
        protected $statistics;

	/**
	 * Конструктор
	 */
	public function __construct(array $config)
	{
		
                if ( ! extension_loaded('memcache'))
		{
			throw new Cache_Exception('Memcache PHP extention not loaded');
		}

		parent::__construct($config);
                
		$this->backend = new Memcache;
		
		$this->flags = Arr::get($this->_config, 'compression', FALSE) ? MEMCACHE_COMPRESSED : FALSE;
		$this->statistics = Arr::get($this->_config, 'statistics');                
		$servers = Arr::get($this->_config, 'servers', NULL);

                if ( ! $servers)
		{
			// Throw an exception if no server found
			throw new Cache_Exception('No Memcache servers defined in configuration');
		}

                
		foreach ($servers as $server)
		{


			if ( ! $this->backend->addServer($server['host'], $server['port'], $server['persistent'], $server['weight'], $server['timeout'], $server['retry_interval'], $server['status']))
			{
				throw new Cache_Exception('Memcache could not connect to host \':host\' using port \':port\'', array(':host' => $server['host'], ':port' => $server['port']));
			}
		}

	}

	/**
	 * Метод find не поддеживается, но предусмотрен интерфейсом
	 *
	 * @param $tag
	 * @return exception
	 */
	public function find($tag) {
		/* Метод не поддерживается*/
		throw new BadMethodCallException();
	}

	/**
	 * Возвращает значение ключа. В случае, если ключ не найден, или значения тэгов не совпадают (ключ сброшен) возвращает NULL.
	 * Проверяет значения тэгов, хранящихся в ключах. В случае, если значения различаются ключ считается сброшенным.
	 * Реализует метод работы с тэгами, описанный на странице http://www.smira.ru/2008/10/29/web-caching-memcached-5/
	 *
	 * @param $id
	 * @return NULL or data
	 */
	public function get($id, $default = NULL) {

		$value = $this->backend->get($id);

		/* Если ключ не найден - завершаемся и возвращает NULL*/
		/* Если ключ не найден - завершаемся и возвращает NULL*/
		if ($value === FALSE) {
                    if($this->statistics)
                        $this->statistics($id, 'M');	
                    return NULL;
		}

		/* Если у значения есть тэги - обрабатываем им и проверяем, не изменилось ли их значение*/
		if (!empty($value['tags']) && count($value['tags']) > 0) {
			$expired = false;

			foreach ($value['tags'] as $tag => $tag_stored_value) {
				/* Получаем значение текущее значение тэга*/
				$tag_current_value = $this->get_tag_value ($tag);

				/* И сравниваем это значение с тем, которое хранится в теле элемента кэша*/
				if ($tag_current_value != $tag_stored_value) {
					/* Если значение изменилось - считаем ключ не валидным*/
					$expired = true;
					break;
				}
			}

			/* Если ключ не валидный - возвращаем NULL*/
			if ($expired) {
                            
                            /*Проверяем не формируется ли кеш*/
                            if($this->backend->get("{$id}_lock"))
                            {
                                if($this->statistics)
                                    $this->statistics($id, 'A');
                                return $value['data'];
                            }
                            
                            return NULL;
			}
		}
                if($this->statistics)
                        $this->statistics($id, 'H');
                
              
		return $value['data'];
	}

	/**
	 * "Удаляет" тэг. А именно, увеличивает значение ключа tag_$tag на 1.
	 * Используется для сброса всех ключей с тэгом $tag.
	 * Реализует метод работы с тэгами, описанный на странице http://www.smira.ru/2008/10/29/web-caching-memcached-5/
	 *
	 * @param $tag
	 * @return
	 */
	public function delete_tag($tag) {
		$key = "tag_$tag";
		$tag_value = $this->get_tag_value($tag);

		$this->set($key, microtime(true), NULL, 60*60*24*30);

		return true;
	}

	/**
	 * Возвращает текущее значение тэга. В случае, если тэг не найден, создает его и возвращает значение 1.
	 * Реализует метод работы с тэгами, описанный на странице http://www.smira.ru/2008/10/29/web-caching-memcached-5/
	 * 
	 * @param $tag
	 * @return int
	 */
	private function get_tag_value($tag) {
		$key = "tag_$tag";
		
		$tag_value = $this->backend->get($key);
		
		if ($tag_value === NULL) {
			$tag_value = microtime(true);
			$this->set($key, $tag_value, NULL, 60*60*24*30);

		}

		return $tag_value;
	}

	/**
	 * Добавляет ключ id со значением data, метками tags.
	 * Реализует метод работы с тэгами, описанный на странице http://www.smira.ru/2008/10/29/web-caching-memcached-5/
	 *
	 * @param $id ключ
	 * @param $data данные
	 * @param $tags метки
	 * @param $lifetime время жизни в секундах
	 * @return bool
	 */
	public function set($id, $data, array $tags = NULL, $lifetime = 3600) {
		
                
                if(!$this->backend->add("{$id}_lock", '1', $this->flags, 5))
                {
                    return false;
                }
                else
                {

                    if($this->statistics)
                        $this->statistics($id, 'L');
                }
                    
                /* Если заданы тэги — получаем их текущие значения в $key_tags*/
		if (!empty($tags)) {
			$key_tags = array();

			foreach ($tags as $tag) {
				$key_tags[$tag] = $this->get_tag_value($tag);
			}

			// Запоминаем $key_tags в элемент tags массива
			$key['tags'] = $key_tags;
		}

		$key['data'] = $data;

		if ($lifetime !== 0) {
			$lifetime += time();
		}
                if($this->statistics)
                        $this->statistics($id, 'W');
		return $this->backend->set($id, $key, $this->flags, $lifetime);
	}

	/**
	 * Удаляет ключ $id
	 *
	 * @param $id ID ключа
	 * @param $tag Не используется, но предусмотрен интерфейсом
	 * @return bool
	 */
	public function delete($id, $tag = FALSE) {
		if ($id == TRUE) {
			return $this->backend->flush();
		}
		// Шлем запрос на удаление в драйвер memcached
		return $this->backend->delete($id);
	}

	/**
	 * Метод delete_expired не поддеживается, но предусмотрен интерфейсом
	 *
	 * @param $tag
	 * @return exception
	 */
	public function delete_expired() {
		// Метод не поддерживается
		throw new BadMethodCallException();
	}
        
	public function increment($id, $step = 1)
	{
		return $this->backend->increment($id, $step);
	}
        
	public function decrement($id, $step = 1)
	{
		return $this->backend->decrement($id, $step);
	}
        
        public function delete_all()
	{
		$result = $this->backend->flush();

		// We must sleep after flushing, or overwriting will not work!
		// @see http://php.net/manual/en/function.memcache-flush.php#81420
		sleep(1);

		return $result;
	}
        
        public function _failed_request($hostname, $port)
	{
		if ( ! $this->_config['instant_death'])
			return;

		// Setup non-existent host
		$host = FALSE;

		// Get host settings from configuration
		foreach ($this->_config['servers'] as $server)
		{
			// Merge the defaults, since they won't always be set
			$server += $this->_default_config;
			// We're looking at the failed server
			if ($hostname == $server['host'] and $port == $server['port'])
			{
				// Server to disable, since it failed
				$host = $server;
				continue;
			}
		}

		if ( ! $host)
			return;
		else
		{
			return $this->_memcache->setServerParams(
				$host['host'],
				$host['port'],
				$host['timeout'],
				$host['retry_interval'],
				FALSE, // Server is offline
				array($this, '_failed_request'
				));
		}
	}
        
        private function statistics($key, $param) 
        {
            /*
             * K - тэг не валиден
             * М - кэш отсутствует
             * Н - успешный запрос кеша
             * W - запись и построение нового кеша
             * L - установлена блокировка ключа
             * U - удаление ключа
             * A - кто-то уже заблокировал поэтому отдаем кеш, если построен новый, нет значит старый
             */
            $f = fopen(APPPATH."cache/statistics/$key.txt", 'a+');
            fwrite($f, $param);
            fclose($f);
            
            return true;
        }
}