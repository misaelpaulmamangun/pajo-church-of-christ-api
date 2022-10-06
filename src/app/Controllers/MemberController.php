<?php

namespace App\Controllers;

use PDO, PDOException;

class MemberController extends Controller
{
  public function index($request, $response)
  {
    try {
      $sql = "
      SELECT
        m.first_name,
        m.last_name,
        m.date_of_birth,
        u.username as created_by,
        m.created_at
      FROM
        members m
      LEFT JOIN
        users u
      ON
        u.id = m.created_by
    ";

      $stmt = $this->c->db->query($sql);

      $data = $stmt->fetchAll(PDO::FETCH_OBJ);

      return $response->withJSON([
        'data' => $data,
        'success' => true,
        'status' => 200,
        'length' => count($data)
      ]);
    } catch (PDOException $e) {
      return $response->withJSON([
        'success' => false,
        'message' => $e->getMessage()
      ]);
    }
  }

  public function create($request, $response)
  {
    try {
      $sql = "
        INSERT INTO members (
          first_name,
          last_name,
          date_of_birth,
          created_by,
          created_at
        ) VALUES (
          :first_name,
          :last_name,
          :date_of_birth,
          :created_by,
          :created_at
        )
      ";

      $stmt = $this->c->db->prepare($sql);

      $stmt->execute([
        ':first_name' => $request->getParam('first_name'),
        ':last_name' => $request->getParam('last_name'),
        ':date_of_birth' => $request->getParam('date_of_birth'),
        ':created_by' => $request->getParam('created_by'),
        ':created_by' => date("Y-m-d")
      ]);

      return $response->withJSON([
        'success' => true,
        'status' => 200,
      ]);
    } catch (PDOException $e) {
      return $response->withJSON([
        'success' => false,
        'message' => $e->getMessage()
      ]);
    }
  }

  public function delete($request, $response)
  {
    try {
      $sql = "
        DELETE FROM
          members
        WHERE id = :id
      ";

      $stmt = $this->c->db->prepare($sql);

      $stmt->execute([
        ':id' => $request->getParam('id')
      ]);

      return $response->withJSON([
        'success' => true,
        'status' => 200
      ]);
    } catch (PDOException $e) {
      return $response->withJSON([
        'success' => false,
        'message' => $e->getMessage()
      ]);
    }
  }

  public function update($request, $response)
  {
    try {
      $sql = "
        UPDATE members
        SET
          first_name = :first_name,
          last_name = :last_name,
          date_of_birth = :date_of_birth
        WHERE id = :id
      ";

      $stmt = $this->c->db->prepare($sql);

      $stmt->execute([
        ':id' => $request->getParam('id'),
        ':first_name' => $request->getParam('first_name'),
        ':last_name' => $request->getParam('last_name'),
        ':date_of_birth' => $request->getParam('date_of_birth')
      ]);

      return $response->withJSON([
        'success' => true,
        'status' => 200
      ]);
    } catch (PDOException $e) {
      return $response->withJSON([
        'success' => false,
        'message' => $e->getMessage()
      ]);
    }
  }
}
