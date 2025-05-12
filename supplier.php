<div class="card">
    <div class="card-header">
        <h3 class="card-title">Supplier Records</h3>
    </div>
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Supplier Name</th>
                    <th>Contact Number</th>  <!-- Added new column -->
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM tblsupplier ORDER BY supplier_name ASC";
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $cnt = 1;
                if($query->rowCount() > 0) {
                    foreach($results as $row) {
                ?>
                <tr>
                    <td><?php echo htmlentities($cnt);?></td>
                    <td><?php echo htmlentities($row->supplier_name);?></td>
                    <td><?php echo htmlentities($row->contact_no);?></td>  <!-- Added contact display -->
                    <td><?php echo htmlentities($row->address);?></td>
                    <td>
                        <a href="delete-supplier.php?id=<?php echo $row->id;?>" 
                           onclick="return confirm('Do you want to delete?');"
                           class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>
                <?php 
                    $cnt++;
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>