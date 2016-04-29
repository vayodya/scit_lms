<?php 
    class Work_from_homes extends AppModel{ 
        var $name='Work_from_homes';

        /*public function isOwnedBy($post, $user) {
            return $this->field('id', array('id' => $post, 'leave_' => $user)) === $post;
        }*/
        public $validate = array(
            'From_Date' => array(
            'rule' => array('date', 'ymd'),
            'message' => 'You must provide a deadline in YYYY-MM-DD format.',
            ),
            'To_Date' => array(
            'rule' => array('date', 'ymd'),
            'message' => 'You must provide a deadline in YYYY-MM-DD format.',
            ),
            'wfh_comment' => array(
                'rule' => 'notEmpty',
                'message' => 'Note is needed.'
            )
            
            
       );
        
         ///////////////////////////////////////
         
         public $actsAs = array(
		'Search.Searchable');

/**
 * Field names accepted for search queries.
 *
 * @var array
 * @see SearchableBehavior
 */
	public $filterArgs = array(
		array('name' => 'From Date', 'type' => 'query', 'method' => 'filterTitle'),
		//array('name' => 'status', 'type' => 'string'),
	);
		
/**
 * Constructor
 *
 * @param mixed $id Model ID
 * @param string $table Table name
 * @param string $ds Datasource
 * @access public
 */
	/**public function __construct($id = false, $table = null, $ds = null) {
		$this->statuses = array(
			'open' => __('Open', true),
			'closed' => __('Closed', true));
		$this->categories = array(
			'bug' => __('Bug', true),
			'support' => __('Support', true),
			'technical' => __('Technical', true),
			'other' => __('Other', true));
		parent::__construct($id, $table, $ds);
	}**/
	
	public function filterTitle($data, $field = null) {
		if (empty($data['From_Date']['year']) || empty($data['From_Date']['month']) || empty($data['From_Date']['day'])
            
               || empty($data['To_Date']['year']) || empty($data['To_Date']['month']) || empty($data['To_Date']['day'])) {
			return array();
		}
               $fd = $data['From_Date']['year']."-".$data['From_Date']['month']."-".$data['From_Date']['day'];
                $td = $data['To_Date']['year']."-".$data['To_Date']['month']."-".$data['To_Date']['day'];
		//$title = $data['Eid'];
               
		return array(
                         'AND' => array($this->alias . '.wfh_states LIKE' => "accepted",
			'OR'  => array( 'AND' => array(
				$this->alias . '.From_Date <=' => $td,
				$this->alias . '.To_Date >=' => $td),
                        
                         array( 'AND' => array(
				$this->alias . '.From_Date <=' => $fd,
				$this->alias . '.To_Date >=' => $fd)),
                             
                        array( 'AND' => array(
				$this->alias . '.From_Date <=' => $fd,
				$this->alias . '.To_Date >=' => $td)),
                             
                         array( 'AND' => array(
				$this->alias . '.From_Date >=' => $fd,
				$this->alias . '.To_Date <=' => $td))
			)));
	}
	
         
         
    } 
?>