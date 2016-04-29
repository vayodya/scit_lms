
 <?php
 
 class leave_record extends AppModel{
     
     var $name = 'leave_record';
     
//     var $virtualFields = array(   //setting sum of the real_days as virtual field
//         'totalRealLeaves' => 'sum(leave_record.real_days)'
//     );
         
         public $validate = array(
            'From_Date' => array(
            'rule' => array('date', 'ymd'),
            'message' => 'You must provide a deadline in YYYY-MM-DD format.',
            ),
            'To_Date' => array(
            'rule' => array('date', 'ymd'),
            'message' => 'You must provide a deadline in YYYY-MM-DD format.',
            ),
            'Leave_Time' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A password is required',
                )
            ),
            'Leave_comment' => array(
                'rule' => 'notEmpty',
                'message' => 'Purpose is required',
                
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
		array('name' => 'From_Date', 'type' => 'query', 'method' => 'filterTitle3'),
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
                
                debug($data);
                exit();
               
		return array(
                         'AND' => array($this->alias . '.Leave_states LIKE' => "accepted",
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