<?php

namespace App\Controllers;

use PDO, PDOException;

class MemberController extends Controller
{
  const MEMBER_ID = "id";
  const FIRST_NAME = "firstName";
  const LAST_NAME = "lastName";
  const DATE_OF_BIRTH = "dateOfBirth";
  const CREATED_AT = "createdAt";
  const USER_ID = "userId";

  public function index($request, $response)
  {
    try {
      $sql = "
      SELECT
        m.firstName,
        m.lastName,
        m.dateOfBirth,
        u.username as createdBy,
        m.createdAt
      FROM
        members m
      LEFT JOIN
        users u
      ON
        u.id = m.userId
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
      $sql = "INSERT INTO members (%s) VALUES (%s)";

      $columns = array(
        self::USER_ID,
        self::FIRST_NAME,
        self::LAST_NAME,
        self::DATE_OF_BIRTH,
        self::CREATED_AT
      );

      $values = array_map(function ($columns) {
        return ':' . $columns;
      }, $columns);

      $sql = vsprintf($sql, array(
        implode(",", $columns),
        implode(",", $values)
      ));

      $stmt = $this->c->db->prepare($sql);

      $stmt->execute([
        self::FIRST_NAME => $request->getParam(self::FIRST_NAME),
        self::LAST_NAME => $request->getParam(self::LAST_NAME),
        self::DATE_OF_BIRTH => $request->getParam(self::DATE_OF_BIRTH),
        self::USER_ID => $request->getParam(self::USER_ID),
        self::CREATED_AT => date("Y-m-d")
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
        WHERE id = :%s
      ";

      $sql = vsprintf($sql, array(
        self::MEMBER_ID
      ));

      $stmt = $this->c->db->prepare($sql);

      $stmt->execute([
        self::MEMBER_ID => $request->getParam(self::MEMBER_ID)
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
          firstName = :%s,
          lastName = :%s,
          dateOfBirth = :%s
        WHERE id = :%s
      ";

      $sql = vsprintf($sql, array(
        self::FIRST_NAME,
        self::LAST_NAME,
        self::DATE_OF_BIRTH,
        self::MEMBER_ID
      ));

      $stmt = $this->c->db->prepare($sql);

      $stmt->execute([
        self::MEMBER_ID => $request->getParam(self::MEMBER_ID),
        self::FIRST_NAME => $request->getParam(self::FIRST_NAME),
        self::LAST_NAME => $request->getParam(self::LAST_NAME),
        self::DATE_OF_BIRTH => $request->getParam(self::DATE_OF_BIRTH)
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
