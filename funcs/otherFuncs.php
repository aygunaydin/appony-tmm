
//getAllTasks is used to list all tasks 
function getAllTasksOLD(){

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();

$sqlGAC = "select a.*,b.subscriber_name
from te_tasks a, te_subscribers b
where a.created_by_id=b.sbscriber_id
";
$rowsGAC = $db->fetch_all_array($sqlGAC);

foreach($rowsGAC as $recordGAC){
echo "<table border=\"1\" bordercolor=\"#FFFFFF\" style=\"background-color:#58D3F7\" width=\"50%\" cellpadding=\"1\" cellspacing=\"3\">\n"; 
echo "	\n"; 
echo "	<tr>\n"; 
echo "		<td colspan=4> Task Name: <a href=comHome.php?com=$recordGAC[task_id]>$recordGAC[task_name]</a></td>\n"; 
echo "	</tr>\n"; 
echo "<tr>\n"; 
echo "		<td colspan=4> Details:$recordGAC[task_details]</td>\n"; 
echo "	</tr>\n"; 
echo "	<tr>\n"; 
echo "		<td>Creator:$recordGAC[subscriber_name] </td>\n"; 
echo "		<td>Status: $recordGAC[status]</td>\n"; 
echo "		<td>Due Date: $recordGAC[creation_date]</td>\n"; 
echo "		<td><a href=appForTask.php?task=$recordGAC[task_id]&islem=2>APPLY</a></td>\n"; 

echo "	</tr>\n"; 
echo "</table>\n\n</br>\n";

}
}

function getNewTasks($userID){

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();

$sqlGAC = "select a.*,b.subscriber_name, c.action_name
from te_tasks a, te_subscribers b, ref_actions c
where a.created_by_id=b.sbscriber_id and a.status in(0,1) and a.created_by_id!=$userID
and a.status=c.related_status
and a.task_id not in (select task_id from te_task_owners where responsible_id=$userID)" ;
$rowsGAC = $db->fetch_all_array($sqlGAC);

foreach($rowsGAC as $recordGAC){
echo "<table border=\"1\" bordercolor=\"#FFFFFF\" align=\"center\" style=\"background-color:#58D3F7\" width=\"50%\" cellpadding=\"1\" cellspacing=\"3\">\n"; 
echo "	\n"; 
echo "	<tr>\n"; 
echo "		<td colspan=4> Task Name: <a href=comHome.php?com=$recordGAC[task_id]>$recordGAC[task_name]</a></td>\n"; 
echo "	</tr>\n"; 
echo "<tr>\n"; 
echo "		<td colspan=4> Details:$recordGAC[task_details]</td>\n"; 
echo "	</tr>\n"; 
echo "	<tr>\n"; 
echo "		<td>Creator:$recordGAC[subscriber_name] </td>\n"; 
echo "		<td>Status: $recordGAC[action_name]</td>\n"; 
echo "		<td>Due Date: $recordGAC[creation_date]</td>\n"; 
echo "		<td><a href=appForTask.php?task=$recordGAC[task_id]&islem=2>APPLY</a></td>\n"; 

echo "	</tr>\n"; 
echo "</table>\n\n</br>\n";

}
}

function getTask($taskID){

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();

$sqlGAC = "select a.*,b.subscriber_name,c.action_name
from te_tasks a, te_subscribers b, ref_actions c
where a.created_by_id=b.sbscriber_id and a.task_id=$taskID and a.status=c.related_status
";
$rowsGAC = $db->fetch_all_array($sqlGAC);

foreach($rowsGAC as $recordGAC){
echo "<table align=\"center\" border=\"1\" bordercolor=\"#FFFFFF\" style=\"background-color:#58D3F7\" width=\"50%\" cellpadding=\"1\" cellspacing=\"3\">\n"; 
echo "	\n"; 
echo "	<tr>\n"; 
echo "		<td colspan=3> Task Name: <a href=comHome.php?com=$recordGAC[task_id]>$recordGAC[task_name]</a></td>\n"; 
echo "	</tr>\n"; 
echo "<tr>\n"; 
echo "		<td colspan=3> Details:$recordGAC[task_details]</td>\n"; 
echo "	</tr>\n"; 
echo "	<tr>\n"; 
echo "		<td>Creator:$recordGAC[subscriber_name] </td>\n"; 
echo "		<td>Status: $recordGAC[action_name]</td>\n"; 
echo "		<td>Creation Date: $recordGAC[creation_date]</td>\n"; 

echo "	</tr>\n"; 
echo "</table>\n\n</br>\n";

}
}

function getTaskHistory($taskID){
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();
$j='1';
$sqlGAC = "select * from te_task_history where task_id=$taskID";
$rowsGAC = $db->fetch_all_array($sqlGAC);

echo "<table border=\"1\" bordercolor=\"#81BEF7\" align=\"center\" width=\"60%\" cellpadding=\"1\" cellspacing=\"3\">\n"; 
echo "	<tr BGCOLOR=\"#F3F781\">\n"; 
echo "		<td > ID"; 
echo "		<td > ACTION NAME</td>\n"; 
echo "		<td>ACTION OWNER </td>\n"; 
echo "		<td>ACTION DATE</td>\n"; 
echo "	</tr>\n"; 
$i=0;


foreach($rowsGAC as $recordGAC){
$i++;
//detecting task owner name from task owner ID
$name=getUserName($recordGAC[task_owner]);
//row background color for visuality.
if (($i%2) ==0) {$BCK="#81BEF7";} else {$BCK="#00BFFF";}
echo "	<tr BGCOLOR=$BCK>\n"; 
echo "		<td > $j"; 
echo "		<td > $recordGAC[action_name]</td>\n"; 
echo "		<td>".$name." </td>\n"; 
echo "		<td>$recordGAC[last_action_date]</td>\n"; 
echo "	</tr>\n"; 
$j++;

}
echo "</table>\n\n</br>\n";

}

//list all subscribers

function getAllSbscribers(){

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();

$sqlGAC = "select * from te_subscribers";
$rowsGAC = $db->fetch_all_array($sqlGAC);

echo "</br> </br><table align=\"center\" border=\"1\" bordercolor=\"#81BEF7\" width=\"60%\" cellpadding=\"1\" cellspacing=\"3\">\n"; 
echo "	<tr BGCOLOR=\"#F3F781\">\n"; 
echo "		<td > ID"; 
echo "		<td > NAME</td>\n"; 
echo "		<td>eMAIL </td>\n"; 
echo "		<td>SKILLS</td>\n"; 
echo "		<td>Registration Date</td>\n"; 
echo "	</tr>\n"; 
$i=0;


foreach($rowsGAC as $recordGAC){
$i++;
if (($i%2) ==0) {$BCK="#81BEF7";} else {$BCK="#00BFFF";}
echo "	<tr BGCOLOR=$BCK>\n"; 
echo "		<td > $recordGAC[sbscriber_id]"; 
echo "		<td > $recordGAC[subscriber_name]</td>\n"; 
echo "		<td>$recordGAC[email] </td>\n"; 
echo "		<td>$recordGAC[skills]</td>\n"; 
echo "		<td>$recordGAC[subscription_date]</td>\n"; 
echo "	</tr>\n"; 
}
echo "</table>\n\n</br>\n";

}

function getUserID($username){

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();

$sqlGAC = "select b.sbscriber_id
from te_subscribers b
where subscriber_name='$username'";
$rowsGAC = $db->fetch_all_array($sqlGAC);

foreach($rowsGAC as $recordGAC){
$userID=$recordGAC[sbscriber_id];
return $userID;
}
}

function getUserName($userID){
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();
$sqlGAC = "select * from te_subscribers b where sbscriber_id='$userID'";
$rowsGAC = $db->fetch_all_array($sqlGAC);
foreach($rowsGAC as $recordGAC){
$userName=$recordGAC[subscriber_name];
return $userName;
}
}

function getTaskOwner($taskID){

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();

$sqlGAC = "select * from te_tasks where task_id=$taskID";
$rowsGAC = $db->fetch_all_array($sqlGAC);
foreach($rowsGAC as $recordGAC){
$ownerID=$recordGAC[created_by_id];
return $ownerID;
}
}

function getMyMailCount($userid)
{

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();

$sqlGAC = "select count(1) as sayi from te_messages where to_subs_id=$userid and is_read=0";
$rowsGAC = $db->fetch_all_array($sqlGAC);
foreach($rowsGAC as $recordGAC){
$sayi=$recordGAC[sayi];
echo "($sayi)";
return $sayi;
}
}


//applyFor function is used to process an appliance and inform the task owner that somebody has applied for his/her task.
function applyFor($requestor,$taskid,$ownerid,$text)
{
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();
$data['task_id'] = $taskid;
$data['owner_id'] = $ownerid; 
$data['responsible_id'] = $requestor;
$data['related_action_id']="1";
$data['is_active']="1";
$primary_id = $db->query_insert("te_task_owners", $data);
//te_task_owners record is created to log that a subscriber applied for the task
$data2['task_id'] = $taskid;
$data2['task_owner'] = $requestor;
$data2['action_id'] = "1"; 
$data2['action_name'] = "SUGGEST SOLUTION"; 
$data2['last_action_date']="NOW()";
//data2 array is created to save a record in te_task_history table.
$primary_id = $db->query_insert("te_task_history", $data2);
//te_task_history record is created, this record will be used while tracking task history.

$data3['status'] = "1";
$db->query_update('te_tasks', $data3, "task_id=$taskid");
//Task status is updated in table te_tasks

$reqName=getUserName($requestor);
$taskName=getTaskName($taskid);
//requestor name is get and will be embedded into the message
$message=$reqName." has applied for ".$taskName.". You should grant ".$reqName." to generate a solution for this task with this message:\" ".$text."
\"Click <a href=\"appForTask.php?islem=3&taskID=$taskid&requestor=$requestor&owner=$ownerid\">HERE</a> to grant ".$reqName."."; 
//a message creted to be sent to the task owner to inform somebody has applied for task
inform($requestor,$ownerid,$taskid,$message);
//echo "</br>DEBUG: applied for message: ".$message."</br>";
//message sent
echo "<form>     
<input type=\"button\" value=\"Show Task History\" onClick=\"showTaskHistory('table1Div',$taskid);\"> 
</form>"; 
echo "<div id=\"table1Div\">";
echo" </div>";
return $primary_id;
}


function grantForSolution($requestor,$taskid,$ownerid)
{
$status=getTaskStatus($taskid);
if($status==1){
$mesName=getUserName($requestor);
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();
$data['task_id'] = $taskid;
$data['owner_id'] = $ownerid; 
$data['responsible_id'] = $requestor;
$data['related_action_id']="2";
$data['is_active']="1";
$primary_id = $db->query_insert("te_task_owners", $data);
//te_task_owners record is created to log that a subscriber is granted for the task
$data2['task_id'] = $taskid;
$data2['task_owner'] = $ownerid;
$data2['action_id'] = "1"; 
$data2['action_name'] = "IN PROGRESS, $mesName is granted."; 
$data2['last_action_date']="NOW()";
$primary_id = $db->query_insert("te_task_history", $data2);
//te_task_history record is created, this record will be used while tracking task history.

$data3['status'] = "2";
$db->query_update('te_tasks', $data3, "task_id=$taskid");
//Task status is updated in table te_tasks

$reqName=getUserName($ownerid);
$message=$reqName." has grant you for \"".getTaskName($taskid)."\". You have to send him solution by usign the following link:
CLICK <a href=\"appForTask.php?islem=4&taskID=$taskid&requestor=$requestor&owner=$ownerid\">HERE </a> TO SEND A SOLUTION! "; 
//a message creted to be sent to the task owner to inform somebody has applied for task
inform($ownerid,$requestor,$taskid,$message);
//echo "</br>DEBUG: grant message: ".$message."</br>";
$requName=getUserName($requestor);
echo "<center>$requName is granted to produce a solution for \"".getTaskName($taskid)."\"";
//message sent
echo "<form>     
<input type=\"button\" value=\"Show Task History\" onClick=\"showTaskHistory('table1Div',$taskid);\"> 
</form></center>"; 
echo "<div id=\"table1Div\">";
echo" </div>";
return $primary_id;
} else {echo "</br><b>ERROR</b>: You should grant a subscriber one time. Please check Task History";echo "<form>     
<input type=\"button\" value=\"Show Task History\" onClick=\"showTaskHistory('table1Div',$taskid);\"> 
</form>"; 
echo "<div id=\"table1Div\">";
echo" </div>";
}
}

function submit($requestor,$taskid,$ownerid,$text)
{
$status=getTaskStatus($taskid);
if($status==2 || $status==4){
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();
$data['task_id'] = $taskid;
$data['owner_id'] = $ownerid; 
$data['responsible_id'] = $ownerid;
$data['related_action_id']="3";
$data['is_active']="1";
$primary_id = $db->query_insert("te_task_owners", $data);
//te_task_owners record is created to log that a subscriber is granted for the task
$data2['task_id'] = $taskid;
$data2['task_owner'] = $requestor;
$data2['action_id'] = "3"; 
$data2['action_name'] = "SUBMITTED"; 
$data2['last_action_date']="NOW()";
$primary_id = $db->query_insert("te_task_history", $data2);
//te_task_history record is created, this record will be used while tracking task history.

$data3['status'] = "3";
$db->query_update('te_tasks', $data3, "task_id=$taskid");
//Task status is updated in table te_tasks
$reqName=getUserName($requestor);
$message=$reqName." has submiitted the olution for \"".getTaskName($taskid)."\". Solution is \"".$text."\".</br>CLICK 
<a href=\"appForTask.php?islem=6&taskID=$taskid&requestor=$requestor&owner=$ownerid\">HERE </a> TO CLOSE! 
</br>CLICK <a href=\"appForTask.php?islem=7&taskID=$taskid&requestor=$requestor&owner=$ownerid\">HERE </a> TO REJECT!";
//a message creted to be sent to the task owner to inform requestor has ent the solution. Owner should reject the solution or should end the task life cyle
inform($requestor,$ownerid,$taskid,$message);
//message sent
//echo "</br>DEBUG: grant message: ".$message."</br>";
echo "Solution is sent for  \"".getTaskName($taskid)."\"";
echo "<form>     
<input type=\"button\" value=\"Show Task History\" onClick=\"showTaskHistory('table1Div',$taskid);\"> 
</form>"; 
echo "<div id=\"table1Div\">";
echo" </div>";
return $primary_id;
} else {echo "<center></br><b>ERROR</b>: Task is already submitted or closed. Please check Task History";echo "<form>     
<input type=\"button\" value=\"Show Task History\" onClick=\"showTaskHistory('table1Div',$taskid);\"> 
</form></center>"; 
echo "<div id=\"table1Div\">";
echo" </div>";
}
}

function close($requestor,$taskid,$ownerid)
{$status=getTaskStatus($taskid);
if($status==3){
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();
$data['task_id'] = $taskid;
$data['owner_id'] = $ownerid; 
$data['responsible_id'] = $ownerid;
$data['related_action_id']="5";
$data['is_active']="0";
$primary_id = $db->query_insert("te_task_owners", $data);
//te_task_owners record is created to log that a subscriber is granted for the task
$data2['task_id'] = $taskid;
$data2['task_owner'] = $ownerid;
$data2['action_id'] = "5"; 
$data2['action_name'] = "CLOSED"; 
$data2['last_action_date']="NOW()";
$primary_id = $db->query_insert("te_task_history", $data2);
//te_task_history record is created, this record will be used while tracking task history.

$data3['status'] = "5";
$db->query_update('te_tasks', $data3, "task_id=$taskid");
//Task status is updated in table te_tasks

$reqName=getUserName($ownerid);
$message="Congraculations ".$reqName." has approved your solution for \"".getTaskName($taskid)."\". Task is closed.";
//a message creted to be sent to the task owner to inform requestor has ent the solution. Owner should reject the solution or should end the task life cyle
inform($ownerid,$requestor,$taskid,$message);
//message sent
//echo "</br>DEBUG: grant message: ".$message."</br>";
//show result
echo "<center>\"".getTaskName($taskid)."\" task is closed.";
echo "<form>     
<input type=\"button\" value=\"Show Task History\" onClick=\"showTaskHistory('table1Div',$taskid);\"> 
</form></center>"; 
echo "<div id=\"table1Div\">";
echo" </div>";
return $primary_id;
} else {echo "</br><b>ERROR</b>: Task is already closed or not submitted yet! Please check Task History";echo "<form>     
<input type=\"button\" value=\"Show Task History\" onClick=\"showTaskHistory('table1Div',$taskid);\"> 
</form>"; 
echo "<div id=\"table1Div\">";
echo" </div>";
}
}

function reject($requestor,$taskid,$ownerid)
{$status=getTaskStatus($taskid);
if($status==3){
$mesName=getUserName($requestor);
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();
$data['task_id'] = $taskid;
$data['owner_id'] = $ownerid; 
$data['responsible_id'] = $ownerid;
$data['related_action_id']="4";
$data['is_active']="0";
$primary_id = $db->query_insert("te_task_owners", $data);
//te_task_owners record is created to log that a subscriber is granted for the task
$data2['task_id'] = $taskid;
$data2['task_owner'] = $ownerid;
$data2['action_id'] = "4"; 
$data2['action_name'] = "REJECTED, $mesName is granted again"; 
$data2['last_action_date']="NOW()";
$primary_id = $db->query_insert("te_task_history", $data2);
//te_task_history record is created, this record will be used while tracking task history.

$data3['status'] = "4";
$db->query_update('te_tasks', $data3, "task_id=$taskid");
//Task status is updated in table te_tasks

$reqName=getUserName($ownerid);
$message=" ".$reqName." has rejected your solution for \"".getTaskName($taskid)."\".  
CLICK <a href=\"appForTask.php?islem=4&taskID=$taskid&requestor=$requestor&owner=$ownerid\">HERE </a> TO SEND A NEW SOLUTION!";
//a message creted to be sent to the task owner to inform requestor has ent the solution. Owner should reject the solution or should end the task life cyle
inform($ownerid,$requestor,$taskid,$message);
//message sent
//echo "</br>DEBUG: grant message: ".$message."</br>";
//show result
echo "<center>\"".getTaskName($taskid)."\" task is rejected.</br>";
echo "<form>     
<input type=\"button\" value=\"Show Task History\" onClick=\"showTaskHistory('table1Div',$taskid);\"> 
</form></center>"; 
echo "<div id=\"table1Div\">";
echo" </div>";
return $primary_id;
} else {echo "</br><b>ERROR</b>: Task is already closed or not submitted yet! Please check Task History";echo "<form>     
<input type=\"button\" value=\"Show Task History\" onClick=\"showTaskHistory('table1Div',$taskid);\"> 
</form>"; 
echo "<div id=\"table1Div\">";
echo" </div>";
}
}



function inform($sender,$receiver,$taskid,$message)
{
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();
$data['from_subs_id'] = $sender;
$data['to_subs_id'] = $receiver; 
$data['text'] = $message;
$data['post_date']="NOW()";
$data['is_read']="0";
$data['task_id']=$taskid;
$primary_id = $db->query_insert("te_messages", $data);
//echo $primary_id;
return $primary_id;
}





//registration function
function register($username,$email,$password,$skills)
{
//if(eregi("^[:alpha:]*[,][:alpha:]*$",$skills)){
if(eregi("^[a-z0-9_-]+[a-z0-9_.-]*[@][a-z0-9_-]+[a-z0-9_.-]*[.][a-z]{2,5}$", $email)){
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();
$data['subscriber_name'] = $username;
$data['email'] = $email;
$data['password']=$password;
$data['skills']=$skills;
$data['subscription_date']="NOW()";
$data['updated_date']="NOW()";
$data['status']="1";
$primary_id = $db->query_insert("te_subscribers", $data);
//echo $primary_id;
return $primary_id;
echo "<center>You are now registered, click <a href=\"login.php\">here</a> to start!</center>";

}
else 
{
echo "<center>ERROR: please go back to <a href=\"login.php?newReg=2\">registration page</a> and enter a valid e-mail address.</center>";
}
//} else {
//echo "<center>ERROR: enter at least two skills seperated with comma ','. Please try again; <a href=\"login.php?newReg=2\">registration page</a> </center>";}
}

//createTask is used to add a new task 
function createTask($name,$owner,$description)
{
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();
$data['task_name'] = $name;
$data['created_by_id'] = $owner; 
$data['creation_date'] = "NOW()";
$data['updated_date']="NOW()";
$data['status']="0";
$data['task_details']=$description;
$primary_id1 = $db->query_insert("te_tasks", $data);

$data2['task_id'] = $primary_id1;
$data2['task_owner'] = $owner;
$data2['action_id'] = "0"; 
$data2['action_name'] = "TASK CREATED";
$data2['last_action_date']="NOW()";
$primary_id = $db->query_insert("te_task_history", $data2);
echo "<center>TASK SAVED! <form>     
<input type=\"button\" value=\"Show Task\" onClick=\"showTask('tableDiv',$primary_id1);\"> </form></center>"; 
echo "<div id=\"tableDiv\">";
echo" </div>";
return $primary_id;
}


function getTaskName($taskID){

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();

$sqlGAC = "select * from te_tasks where task_id=$taskID";
$rowsGAC = $db->fetch_all_array($sqlGAC);
foreach($rowsGAC as $recordGAC){
$taskName=$recordGAC[task_name];
return $taskName;
}
}


function controlUserName($username)
{
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();

$sqlGAC = "select count(1) as adet from te_subscribers where subscriber_name='$username'";
$rowsGAC = $db->fetch_all_array($sqlGAC);
foreach($rowsGAC as $recordGAC){
$status=$recordGAC[adet];
return $status;

}
}

function getTaskStatus($taskID){

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();

$sqlGAC = "select * from te_tasks where task_id=$taskID";
$rowsGAC = $db->fetch_all_array($sqlGAC);
foreach($rowsGAC as $recordGAC){
$status=$recordGAC[status];
return $status;
}
}


//getSubsName

function getMessages($userid){
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();
$sqlGAC = "select * from te_messages where to_subs_id=$userid order by post_date desc";
$rowsGAC = $db->fetch_all_array($sqlGAC);
// i is used to determine background color of the row for extended visiuality
$i=0;
foreach($rowsGAC as $recordGAC){
echo "<table width=\"75%\" align=\"center\" border=\"1\" >";
$i++;
$taskName=getTaskName($recordGAC[task_id]);
$fromName=getUserName($recordGAC[from_subs_id]);
if (($i%2) ==0) {$BCK="#BBAA00";} else {$BCK="#81BEF7";}
echo "	<tr BGCOLOR=$BCK>\n"; 
echo "		<td width=\"50%\"> <b>TASK :</b> ". $taskName."</td>"; 
//show Profile button is added to show senders profile in a new division located under the message view
echo "		<td ><b>Sender: </b> $fromName</td>";
echo "<td > DATE:". $recordGAC[post_date];
//if message is unread, show mark as read button
if($recordGAC[is_read]=='0') { 
//mark as read button
} 
echo "  </td>";
echo "	</tr>\n"; 
echo "	<tr BGCOLOR=$BCK>\n"; 
echo "		<td colspan='2' width=\"80%\" > ". $recordGAC[text]."</td>";
$shP=$recordGAC[from_subs_id];
echo "		<td><form> 
<input type=\"button\" value=\"Show $fromName's Profile\" onClick=\"showProfile('tableDiv$division$i',$shP);\"> 
<input type=\"button\" value=\"Hide\" onClick=\"hideDetails('tableDiv$division$i','1');\">
</form></td>";
echo "	</tr></br>\n"; 
echo "</table>\n\n</br>\n";

echo "<div id=\"tableDiv$division$i\">";
echo" </div>";
}

}

//getMyTasks is used to list task created or owned by the user
function getMyTasks($userid){
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();

$sqlGAC = "select a.*,b.subscriber_name,c.action_name
from te_tasks a, te_subscribers b, ref_actions c
where a.created_by_id=b.sbscriber_id and b.sbscriber_id=$userid and a.status=c.related_status";
$rowsGAC = $db->fetch_all_array($sqlGAC);
echo "</br></br></br><table align=\"center\" style=\"background-color:#00EEF7\"  width=\"75%\" >
<tr><td> created by you: </td></tr>
<tr><td align=\"right\"><input type=\"button\" value=\"Hide My Tasks\" onClick=\"hideDetails('cByYou','1');\">
<input type=\"button\" value=\"Reload\" onClick=\"javascript:history.go(0);\"\"></td></tr></table>	\n"; 
$division=0;
$division2=0;
echo "<div id=\"cByYou\">";
foreach($rowsGAC as $recordGAC){
$division++;

echo "<table border=\"1\" align=\"center\"  bordercolor=\"#FFFFFF\" style=\"background-color:#58D3F7\" width=\"75%\" cellpadding=\"1\" cellspacing=\"3\">\n"; 
echo "	<tr>\n"; 
$task_ID=$recordGAC[task_id];
echo "		<td> Task Name:</td><td colspan=3> $recordGAC[task_name]</td>\n"; 
echo "	</tr>\n"; 
echo "<tr>\n"; 
echo "		<td> Details:</td><td colspan=3>$recordGAC[task_details]</td>\n"; 
echo "	</tr>\n"; 
echo "	<tr>\n"; 
echo "		<td>Creator: $recordGAC[subscriber_name] </td>\n"; 
echo "		<td>Status: $recordGAC[action_name]</td>\n"; 
echo "		<td>Due Date: $recordGAC[creation_date]</td>\n"; 
echo "<td><form>     
<input type=\"button\" value=\"Show Details\" onClick=\"showDetails('tableDiv$division',$task_ID);\"> 
<input type=\"button\" value=\"Hide Details\" onClick=\"hideDetails('tableDiv$division',$task_ID);\"> 
</form></td>"; 
echo "	</tr>\n"; 
echo "</table>\n\n</br>\n\n";
echo "<div id=\"tableDiv$division\">";
echo" </div>";
}echo "</div>";

echo "<table border=\"1\" align=\"center\"  bordercolor=\"#FFFFFF\" style=\"background-color:#58D3F7\" width=\"75%\" cellpadding=\"1\" cellspacing=\"3\">\n"; 
$sqlGAC1 = "select a.*,c.action_name from te_tasks a,ref_actions c where task_id in (select task_id from te_task_owners where responsible_id=$userid)
and task_id not in (select task_id from te_tasks where created_by_id=$userid) and a.status=c.related_status";

echo "</br></br></br><table align=\"center\"  style=\"background-color:#55FFE5\" width=\"75%\" ><tr><td> Assiged to you (at least one time)</td></tr>
<tr><td align=\"right\"><input type=\"button\" value=\"Hide Owned Tasks\" onClick=\"hideDetails('aToYou','1');\">
<input type=\"button\" value=\"Reload\" onClick=\"javascript:history.go(0);\"\"></td></tr></table> </td></tr></table>\n"; 
$rowsGAC = $db->fetch_all_array($sqlGAC1);
echo "<div id=\"aToYou\">";
foreach($rowsGAC as $recordGAC){
$division2++;
$task_ID=$recordGAC[task_id];
echo "<table border=\"1\" align=\"center\"  bordercolor=\"#FFFFFF\" style=\"background-color:#58D3F7\"  width=\"75%\" cellpadding=\"1\" cellspacing=\"3\">\n"; 
echo "	<tr>\n"; 
echo "		<td> Task Name:<td colspan=3>$recordGAC[task_name]</td>\n"; 
echo "	</tr>\n"; 
echo "<tr>\n"; 
echo "		<td> Details:<td colspan=3>$recordGAC[task_details]</td>\n"; 
echo "	</tr>\n"; 
echo "	<tr>\n"; 
echo "		<td>Creator:$recordGAC[subscriber_name] </td>\n"; 
echo "		<td>Status: $recordGAC[action_name]</td>\n"; 
echo "		<td>Due Date: $recordGAC[creation_date]</td>\n";
echo "		<td><form>     
<input type=\"button\" value=\"Show Details\" onClick=\"showDetails('tableDiv2$division2',$task_ID);\"> 
<input type=\"button\" value=\"Hide Details\" onClick=\"hideDetails('tableDiv2$division2',$task_ID);\"> 
</form></td>";
echo "	</tr>\n"; 
echo "</table>\n\n</br>\n";
echo "<div id=\"tableDiv2$division2\">";
echo" </div>";
}
echo "</div>";
}


function getAllTasks(){
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();

$sqlGAC = "select a.*,b.subscriber_name,c.action_name
from te_tasks a, te_subscribers b, ref_actions c
where a.created_by_id=b.sbscriber_id and a.status=c.related_status order by creation_date desc";
$rowsGAC = $db->fetch_all_array($sqlGAC);
echo "</br></br></br><table align=\"center\" style=\"background-color:#00EEF7\"  width=\"75%\" >
<tr><td> ALL TASKS (in descending order according to task creation time)</td></tr>
<tr><td align=\"right\"><input type=\"button\" value=\"Hide Tasks\" onClick=\"hideDetails('cByYou','1');\">
<input type=\"button\" value=\"Reload\" onClick=\"javascript:history.go(0);\"\"></td></tr></table>	\n"; 
$division=0;
$division2=0;
echo "<div id=\"cByYou\">";
foreach($rowsGAC as $recordGAC){
$division++;

echo "<table border=\"1\" align=\"center\"  bordercolor=\"#FFFFFF\" style=\"background-color:#58D3F7\" width=\"75%\" cellpadding=\"1\" cellspacing=\"3\">\n"; 
echo "	<tr>\n"; 
$task_ID=$recordGAC[task_id];
echo "		<td> Task Name:</td><td colspan=3> $recordGAC[task_name]</td>\n"; 
echo "	</tr>\n"; 
echo "<tr>\n"; 
echo "		<td> Details:</td><td colspan=3>$recordGAC[task_details]</td>\n"; 
echo "	</tr>\n"; 
echo "	<tr>\n"; 
echo "		<td>Creator: $recordGAC[subscriber_name] </td>\n"; 
echo "		<td>Status: $recordGAC[action_name]</td>\n"; 
echo "		<td>Due Date: $recordGAC[creation_date]</td>\n"; 
echo "<td><form>     
<input type=\"button\" value=\"Show Details\" onClick=\"showDetails('tableDiv$division',$task_ID);\"> 
<input type=\"button\" value=\"Hide Details\" onClick=\"hideDetails('tableDiv$division',$task_ID);\"> 
</form></td>"; 
echo "	</tr>\n"; 
echo "</table>\n\n</br>\n\n";
echo "<div id=\"tableDiv$division\">";
echo" </div>";
}
echo "</div>";

}




function getMyprofile($userid){

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
$db->connect();

$sqlGAC = "
select * from te_subscribers where sbscriber_id=$userid";
$rowsGAC = $db->fetch_all_array($sqlGAC);
echo "</br></br><table align=\"center\" border=\"1\" bordercolor=\"#FFFFFF\" style=\"background-color:#58D3F7\" width=\"50%\" cellpadding=\"1\" cellspacing=\"3\" >"; 

foreach($rowsGAC as $recordGAC){
echo "<form action=\"profile.php\">\n"; 
echo "<input type=\"hidden\" value=\"1\" name=\"islem\" >\n"; 
echo "<tr><td>Nickname:</td><td> <input  readonly=\"readonly\"  type=\"text\" name=\"name\" value='".$recordGAC[subscriber_name]."'/></td></tr>\n"; 
echo "<tr><td>Email Adress: </td><td> <input  readonly=\"readonly\"  type=\"text\" name=\"email\" value='".$recordGAC[email]."'/></td></tr>\n"; 
echo "\n"; 
echo "<tr><td>Skills:</td><td> \n"; 
echo "<textarea  readonly=\"readonly\"  name=\"skills\" rows=\"3\" cols=\"30\"> $recordGAC[skills]</textarea> </br></td></tr>\n"; 
echo "<tr><td>Subscription Date: </td><td> $recordGAC[subscription_date] </td></tr>\n"; 
echo "<tr><td>Account Status: </td><td> $recordGAC[status]</td></tr>"; 
echo "\n"; 
echo "</form>\n";
}
echo "</table>";
}


function loginCheck($myusername,$mypassword){
$host="localhost"; // Host name 
$username="ayguncom"; // Mysql username 
$password="ayguncom"; // Mysql password 
$db_name="taskbuilder"; // Database name 
$tbl_name="te_subscribers"; // Table name

// Connect to server and select databse.
mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");

// To protect MySQL injection (more detail about MySQL injection)
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);
$myusername = mysql_real_escape_string($myusername);
$mypassword = mysql_real_escape_string($mypassword);

$sql="SELECT * FROM $tbl_name WHERE subscriber_name='$myusername' and password='$mypassword'";
$result=mysql_query($sql);

// Mysql_num_row is counting table row
$count=mysql_num_rows($result);
// If result matched $myusername and $mypassword, table row must be 1 row

if($count==1){
session_start();
session_register("myusername");
session_register("mypassword"); 
$_SESSION['userName']=$myusername;
header("location:index.php");
}
else {
echo "Wrong Username or Password click <a href=\"login.php\">HERE</a> to retry";
}
}

function layoutMenu($userID){
echo "<center>".$_SESSION['userName'].", welcome to chubuk tracker"; 

echo "<table align=\"center\">\n"; 
echo "<tr>\n"; 
echo "<td align=\"center\" width=\"15%\"><img  src=\"images/home.png\" onClick=\"parent.location='index.php'\"; height=\"45\" width=\"45\"/> </td>\n"; 
echo "<td align=\"center\" width=\"15%\"><img  src=\"images/tasks.png\" onClick=\"showTsubmenu('subMenuDiv');\" height=\"50\" width=\"50\"/> </td>\n"; 
echo "<td align=\"center\" width=\"15%\"><img  src=\"images/profiles.png\" onClick=\"showPsubmenu('subMenuDiv');\" height=\"50\" width=\"50\"/></td>\n"; 
echo "<td align=\"center\" width=\"15%\"><img  src=\"images/inbox.png\" height=\"50\" width=\"50\" onClick=\"parent.location='message.php'\";/></td>\n"; 
echo "<td align=\"center\" width=\"15%\"><img  src=\"images/quickAdd.png\" height=\"50\" width=\"50\" onClick=\"parent.location='addTask.php'\";/></td>\n";
echo "<td align=\"center\" width=\"15%\"><img  src=\"images/FAQ.png\" height=\"50\" width=\"50\" onClick=\"parent.location='FAQ.htm'\";/></td>\n";  
echo "<td align=\"center\" width=\"15%\"><img  src=\"images/logout.png\" height=\"50\" width=\"50\" onClick=\"parent.location='logout.php'\";/></td>\n"; 
echo "</tr>\n"; 
echo "<tr>\n"; 
echo "<td align=\"center\" width=\"15%\" >Home</td>\n"; 
echo "<td align=\"center\" width=\"15%\">Tasks</td>\n"; 
echo "<td align=\"center\" width=\"15%\">Profiles</td>\n"; 
echo "<td align=\"center\" width=\"15%\">Inbox";
getMyMailCount($userID);
echo "</td>\n"; 
echo "<td align=\"center\" width=\"15%\">Quick Add!</td>\n"; 
echo "<td align=\"center\" width=\"15%\">F.A.Q</td>\n"; 
echo "<td align=\"center\" width=\"15%\">Logout</td>\n"; 
echo "</tr>\n"; 
echo "</table>\n"; 
echo "<div id=\"subMenuDiv\">";
echo" </div>";
echo "</center>\n";
}
