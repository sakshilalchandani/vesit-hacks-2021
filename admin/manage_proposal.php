<?php


require('header.inc.php');

$comm_id='';
$event_name = '';
$event_desc = '';
$image='';
$com = '';
$date1 = date("Y-m-d" , strtotime('+12 days'));
$date2 = date("Y-m-d" , strtotime('+14 days'));
$status = 'pending';
#$added_by = 'person';
$com_name = '';
$msg = '';
$com_cat = '';


   # image is treated separately


// to get comm name & category from id
echo $comm_id;
$fsql = "select abbrv,com_category from committee where com_id='$comm_id'";
$fres=mysqli_query($con, $fsql);
$row = mysqli_fetch_assoc($fres);
$com_name = $row['abbrv'];
$com_cat = $row['com_category'];
// end



if (isset($_POST['submit'])){
   $comm_id = get_safe_value($con, $_POST['comm_id']);
   $event_name = get_safe_value($con, $_POST['event_name']);
   $event_desc = get_safe_value($con, $_POST['event_desc']);
   #$com = get_safe_value($con, $_POST['committee']);
   $date1 = get_safe_value($con, $_POST['start_date']);
   $date2 = get_safe_value($con, $_POST['end_date']);
   $status = get_safe_value($con, $_POST['status']);
   $com_name = get_safe_value($con, $_POST['com_name']);
   $com_cat = get_safe_value($con, $_POST['com_cat']);


   # check if the entered product already exists in the db
   $res = mysqli_query($con, "select * from event where event_name='$event_name'");
   $count=mysqli_num_rows($res);
   if ($count>0){       # means already exists
            $msg = 'Event already exists';
         }




   if ($msg == ''){

         # for adding, u have to join proposal & committee.
         #$sql2 = "select * from proposal, committee where proposal.abbrv="   

         /*$last_id = mysqli_insert_id($con);
         $last_id = $last_id + 1;
         echo $last_id;*/
         $sql = "insert into event(event_name,event_desc,comm_id, start_date,end_date,comm_name,comm_type, status) 
         values('$event_name','$event_desc', '$comm_id', '$date1','$date2','$com_name','$com_cat', '$status')"; 
      }
      
   $res = mysqli_query($con, $sql);
   #echo $sql;
   #header('location:proposal.php?key=pending');
   #die();
   
}

?>

<div class="content pb-0">
            <div class="animated fadeIn">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="card">
                        <div class="card-header"><strong> Proposals Form</strong></div>
                        <form method= "post">
                        <div class="card-body card-block">
                            <div class="form-group"><label for="company" class=" form-control-label">Select a Committee</label>
                            <select class="form-control" name="comm_id">
                              <option> select committee </option>
                              <?php

                              # committee wahi lege jiska abbrv == current user ka comm.
                              $curr_admin_com = $_SESSION['admin_comm'];

                              $add_sql = "select distinct com_id,com_name,abbrv from committee order by com_name asc";
                              $add_res=mysqli_query($con, $add_sql);
                              while ($row=mysqli_fetch_assoc($add_res)){
                                    if ($row['abbrv'] == $curr_admin_com){
                                       echo "<option value=".$row['com_id'] . ">" . $row['com_name'] . "</option>";
                                    }
                                 }
                              ?>
                           </select>
                           </div>
                            
                            
                            <div class="form-group"><label for="categories" class=" form-control-label">Proposal Title</label>
                            <input type="text" name="event_name" value="<?php echo $event_name ?>" placeholder="Enter title of event" class="form-control" required>
                            </div>

                            <div class="form-group"><label for="categories" class=" form-control-label">Description</label>
                            <textarea name="event_desc" placeholder="Enter event Description" class="form-control"><?php echo $event_desc ?></textarea>
                            </div>

                            <div class="form-group"><label for="categories" class=" form-control-label" <?php ?> >Image</label>
                            <input type="file" name="image" class="form-control">
                            </div>

                            <div class="form-group"><label for="categories" class=" form-control-label">Start Date</label>
                            <input type="text" name="start_date" value = "<?php echo $date1 ?>" placeholder="Enter tentative dates" class="form-control" required>
                            </div>
                            <div class="form-group"><label for="categories" class=" form-control-label">End Date</label>
                            <input type="text" name="end_date" value = "<?php echo $date2 ?>" placeholder="Enter tentative dates" class="form-control" required>
                            </div>
                            <div class="form-group"><label for="categories" class=" form-control-label">Committee Name</label>
                            <input type="text" name="com_name" value = "<?php echo $com_name ?>" placeholder="Enter Committee Name" class="form-control" required>
                            </div>
                            <div class="form-group"><label for="categories" class=" form-control-label">Committee Category</label>
                            <input type="text" name="com_cat" value = "<?php echo $com_cat ?>" placeholder="Enter Committee Category" class="form-control" required>
                            </div>

                            <div class="form-group"><label for="categories" class=" form-control-label"> Status</label>
                            <input type="text" name="status" value = "<?php echo $status ?>" placeholder="Enter product Name" class="form-control" required>
                            </div>


                            <button id="payment-button" type="submit" name="submit" class="btn btn-lg btn-info btn-block">
                            <span id="payment-button-amount">Submit</span>
                            </button>
                            <div class="field_error"><?php echo $msg ?></div>
                        </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>


<?php
require('footer.inc.php');
?>