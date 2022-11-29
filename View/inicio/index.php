<div class="content-wrapper">
<div class="content">
        <!-- Add card here  -->
        <br>
        <div class="card">
            <div class="card-header">
                Test header
            </div>
            <div class="card-body">
            <div>
                <form id="testform" action="">
                        <div class="form-group">
                        <label for="name">Name</label>
                            <input id="name" type="text" class="form-control" placeholder="Name">
                        </div>
                        <div class="form-group">
                            <label for="lastname">Last name</label>
                            <input id="lastname" type="text" class="form-control" placeholder="Last Name">
                        </div>
                        <div class="form-group">
                            <label for="lastname">Age</label>
                            <input id="age" type="text" class="form-control" placeholder="Age">
                        </div>
                        <div class="form-group">
                            <label for="">Rol</label>
                            <select class="form-control" name="rol" id="rol">
                                <option value="">Choose a option</option>
                            </select>
                        </div>
                        <button class="btn btn-success">Save</button>
                    </form>
                </div>
                <br>
                <div class="row">
                    <div>
                        <br>
                        <tableid="tbl_user">
                            <thead>
                                <th>Name</th>
                                <th>Lastname</th>
                                <th>Age</th>
                                <th>Birthday</th>
                                <th>Rol</th>
                            </thead>
                            <tbody>
                                <?php foreach($users as $user):?>
                                    <tr>
                                        <td><?php echo $user['name']; ?></td>
                                        <td><?php echo $user['lastname']; ?></td>
                                        <td><?php echo $user['age']; ?></td>
                                        <td><?php echo $user['birth_date']; ?></td>
                                        <td><?php echo $user['name_rol']; ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</div>
</div>
<script>
    $(function(){
        $("#testform").on('submit', function(e){
            var $name = $("#name");
            if($name.val()== ""){
                alert('You must complete the name field');
                $name.focus();
                return false;
            }
            alert('Form complete');
            e.preventDefault();
        });
        $.ajax({
            url: "inicio/getRoles",
            type: "POST",
            success: function (response) {
                //data roles
                console.log(response);
            }  
        });
    
    });
</script>