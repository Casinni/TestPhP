<?php
require_once "Constantes.php";
require_once "metier/Adresse.php";

/**
 * 
 *Classe permettant d'acceder en bdd pour inserer supprimer modifier
 * selectionner l'objet Adresse
 * @author pascal Lamy
 *
 */
class AdresseDB
{
	private $db; // Instance de PDO

	public function __construct($db)
	{
		$this->db = $db;
	}
	/**
	 * 
	 * fonction d'Insertion de l'objet Adresse en base de donnee
	 * @param Adresse $p
	 */
	public function ajout(Adresse $a)
	{
		//TODO insertion de l'adresse en bdd
		$q = $this->db->prepare('INSERT INTO adresse(numero,rue,codepostal,ville,id_pers) values(:numero,:rue,:codepostal,:ville,:id_pers)');

		$q->bindValue(':numero', $a->getNumero());
		$q->bindValue(':rue', $a->getRue());
		$q->bindValue(':codepostal', $a->getCodepostal());
		$q->bindValue(':ville', $a->getVille());
		$q->bindValue(':id_pers', $a->getPersId());
		$q->execute();
		$q->closeCursor();
		$q = NULL;
	}
	/**
	 * 
	 * fonction de Suppression de l'objet Adresse
	 * @param Adresse $a
	 */
	public function suppression(Adresse $a)
	{
		//TODO suppression de l'adresse en bdd
		$q = $this->db->prepare('DELETE FROM adresse WHERE numero=:numero AND rue=:rue AND codepostal=:codepostal AND ville=:ville');
		$q->bindValue(':numero', $a->getNumero());
		$q->bindValue(':rue', $a->getRue());
		$q->bindValue(':codepostal', $a->getCodepostal());
		$q->bindValue(':ville', $a->getVille());
		$q->execute();
		$q->closeCursor();
		$q = NULL;
	}
	/** 
	 * Fonction de modification d'une adresse
	 * @param Adresse $a
	 * @throws Exception
	 */
	public function update(Adresse $a)
	{
		try {
			//TODO mise a jour de l'adresse en bdd
			$q = $this->db->prepare('UPDATE adresse SET numero=:numero, rue=:rue, codepostal=:codepostal, ville=:ville WHERE id=:id');
			$q->bindValue(':numero', $a->getNumero());
			$q->bindValue(':rue', $a->getRue());
			$q->bindValue(':codepostal', $a->getCodepostal());
			$q->bindValue(':ville', $a->getVille());
			$q->bindValue(':id', $a->getId());
			$q->execute();
			$q->debugDumpParams();
			$q->closeCursor();
			$q = NULL;
		} catch (Exception $e) {
			//TODO definir constante de l'exception
			throw new Exception(Constantes::EXCEPTION_DB_ADRESSE);
		}
	}
	/**
	 * 
	 * Fonction qui retourne toutes les adresses
	 * @throws Exception
	 */
	public function selectAll()
	{

		//TODO selection de l'adresse
		$query = 'SELECT numero,rue,codepostal,ville FROM adresse';
		$q = $this->db->prepare($query);
		$q->execute();

		$result = $q->fetchAll(PDO::FETCH_CLASS);

		//TODO definir constante de l'exception
		if (empty($result)) {
			throw new Exception(Constantes::EXCEPTION_DB_ADRESSE);
		}

		//Clore la requete préparée
		$q->closeCursor();
		$q = NULL;
		//retour du resultat
		return $result;
	}
	/**
	 * 
	 * Fonction qui retourne l'adresse en fonction de son id
	 * @throws Exception
	 * @param $id
	 */
	public function selectAdresse($id)
	{

		//TODO selection de l'adresse
		$query = 'SELECT * FROM adresse WHERE id=:id';
		$q = $this->db->prepare($query);
		$q->bindValue(':id', $id);
		$q->execute();

		$result = $q->fetch(PDO::FETCH_ASSOC);

		//TODO definir constante de l'exception
		if (empty($result)) {
			throw new Exception(Constantes::EXCEPTION_DB_ADRESSE);
		}

		//Clore la requete préparée
		$q->closeCursor();
		$q = NULL;

		$res = $this->convertPdoAdr($result);

		//retour du resultat
		return $res;

	}
	/**
	 * 
	 * Fonction qui convertie un PDO Adresse en objet Adresse
	 * @param $pdoAdres
	 * @throws Exception
	 */
	public function convertPdoAdr($pdoAdres)
	{
		//TODO conversion du PDO ADRESSE en objet adresse
		if (empty($pdoAdres)) {
			throw new Exception(Constantes::EXCEPTION_DB_CONVERT_ADR);
		}
		$obj = (object)$pdoAdres;
		$adr = new Adresse(
			$obj->numero, 
			$obj->rue, 
			$obj->codepostal, 
			$obj->ville, 
			$obj->id_pers
		);
		$adr->setId($obj->id);
		return $adr;
	}
}
