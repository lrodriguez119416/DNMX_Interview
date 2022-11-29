<div class="content-wrapper">
<div class="content">
        <!-- Add card here  -->
        <br>
        <div class="card">
            <div class="card-header">
                Test header
            </div>
            <div class="card-body">
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