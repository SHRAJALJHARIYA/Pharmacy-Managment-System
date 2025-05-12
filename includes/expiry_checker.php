    <?php
    function checkExpiringMedicines($dbh) {
        $today = date('Y-m-d');
        $ten_days = date('Y-m-d', strtotime($today . ' + 10 days'));
        $three_days = date('Y-m-d', strtotime($today . ' + 3 days'));

        // Get medicines expiring in next 10 days
        $sql = "SELECT p.*, 
                DATEDIFF(p.expirydate, CURDATE()) as days_until_expiry 
                FROM tblproduct p 
                WHERE p.expirydate <= ? 
                AND p.expirydate >= CURDATE() 
                ORDER BY p.expirydate ASC";
        
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$ten_days]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getExpiryAlertClass($days) {
        if ($days <= 3) return 'danger';
        if ($days <= 7) return 'warning';
        if ($days <= 10) return 'info';
        return 'success';
    }

    function checkExpiryOnLoad($dbh) {
        $today = date('Y-m-d');
        $ten_days = date('Y-m-d', strtotime($today . ' + 10 days'));
        
        // Get expiring medicines
        $sql = "SELECT * FROM tblproduct 
                WHERE expirydate <= ? 
                AND expirydate >= CURRENT_DATE()
                ORDER BY expirydate ASC";
                
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$ten_days]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    ?>