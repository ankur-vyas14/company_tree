<?php
class Travel
{
// Enter your code here
  private const API_URL = "https://5f27781bf5d27e001612e057.mockapi.io/webprovise/travels";

  function get_travel_details() {
    
    return file_get_contents(Travel::API_URL);
  
  }
}

class Company
{
// Enter your code here

  private const API_URL = "https://5f27781bf5d27e001612e057.mockapi.io/webprovise/companies";

  function get_company_details() {
    
    return file_get_contents(Company::API_URL);
  
  }
}
$cost = [];
function createTree(&$list, $parent){
  global $cost;
  $tree = array();
  foreach ($parent as $k=>$l){
      if(isset($list[$l->id])){
          $l->cost = $cost[$l->id];
          $l->children = createTree($list, $list[$l->id]);          
      }
      $l->cost = $cost[$l->id];
      unset($l->{"createdAt"});
      unset($l->{"parentId"});
      $tree[] = $l;
  } 
  return $tree;
}

class TestScript
{

    public function execute()
    {
        global $cost;
        $start = microtime(true);
        // Enter your code here
        // echo json_encode($result);
        $company = new Company();
        $travel = new Travel();
        $companies = json_decode($company->get_company_details());
        $travel_data = json_decode($travel->get_travel_details());
        
        $new = array();
        foreach ($companies as $a){
            $new[$a->parentId][] = $a;
        }
        // calculate travel cost
        foreach($travel_data as $travel_cost) {
          if(array_key_exists($travel_cost->companyId, $cost))
            $cost[$travel_cost->companyId] = $cost[$travel_cost->companyId] + $travel_cost->price;
          else  
            $cost[$travel_cost->companyId] = $travel_cost->price;
        }
        $tree = createTree($new, array($companies[0]));
        print_r($tree);
        echo 'Total time: '.  (microtime(true) - $start);
    }
}

(new TestScript())->execute();