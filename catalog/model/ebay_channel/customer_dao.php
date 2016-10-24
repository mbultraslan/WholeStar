<?php
class ModelEbayChannelCustomerDao extends Model {


    public function save($customer) {
        $customer_id = null;
        $dbCustomer = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer  WHERE email = '" . $this->db->escape($customer['email']) . "'")->row;
        if(!empty($dbCustomer)) {
            $customer_id = $dbCustomer['customer_id'];
            if(!empty($customer['firstname']) && !empty($customer['lastname']) )
            $this->db->query("UPDATE `" . DB_PREFIX . "customer` SET firstname = '" . $this->db->escape($customer['firstname']) . "', lastname = '" . $this->db->escape($customer['lastname']) . "' WHERE customer_id='". $this->db->escape($dbCustomer['customer_id']) ."'");
        } else {
            $this->db->query("INSERT INTO " . DB_PREFIX . "customer SET store_id = '" . (int)$this->config->get('config_store_id')
                . "', firstname = '" . $this->db->escape($customer['firstname'])
                . "', lastname = '" . $this->db->escape($customer['lastname'])
                . "', email = '" . $this->db->escape($customer['email'])
                . "', telephone = '" . $this->db->escape($customer['telephone'])
                . "', fax = '" . $this->db->escape($customer['fax'])
                . "', salt = '" . $this->db->escape($customer['salt'])
                . "', password = '" . $this->db->escape($customer['password'])
                . "', newsletter = '0"
                . "', customer_group_id = '" . $this->db->escape($customer['customer_group_id'])
                . "', status = '1', approved = '0"
                . "', date_added = NOW()");

            $customer_id = $this->db->getLastId();

            $this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id
                . "', firstname = '" . $this->db->escape($customer['address']['firstname'])
                . "', lastname = '"  . $this->db->escape($customer['address']['lastname'])
                . "', address_1 = '" . $this->db->escape($customer['address']['address_1'])
                . "', address_2 = '" . $this->db->escape($customer['address']['address_2'])
                . "', postcode = '" . $this->db->escape($customer['address']['postcode'])
                . "', city = '" . $this->db->escape($customer['address']['city'])
                . "', country_id = '" . (int)$customer['address']['country_id'] . "'");

            $address_id = $this->db->getLastId();

            if (!empty($data['default'])) {
                $this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
            }

        }
        return $customer_id;
    }

    public function getCustomerGroups($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $sort_data = array(
            'cgd.name',
            'cg.sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY cgd.name";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }




}
?>
