<?php 

  class Service
  {
  	
  	private $connexion;

	public function __construct($PARAM_hot, $PARAM_port, $PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe)
	{
		$ErreurListe = array('Erreur'=>"Erreur dans le constructeur PDO");
		try{
			$this->connexion = new PDO('mysql:host='.$PARAM_hot.'; port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
			$this->connexion->exec("SET CHARACTER SET utf8");
		}
		
		catch(Exception $e){					
        echo(json_encode($ErreurListe));
		}
	}

	public function verificationDate($date)
	{
			$datetime = date('Y-m-d', $date);
			$tempDate = explode('-', $datetime);
			if(!checkdate($tempDate[1], $tempDate[2], $tempDate[0]))
				return 0;
			else
				return 1;
		
	}

	public function verificationINT($value)
	{
		if(!is_numeric($value))
			return 0;

		if(intval($value) != $value || intval($value) <= 0)
			return 0;

		if(!is_int(intval($value)))
			return 0;

		if(is_int(intval($value)) <= 0)
			return 0;

		return 1;
	}

	public function verificationFloat($value)
	{
		if(!is_numeric($value))
			return 0;

		if(floatval($value) != $value || floatval($value) <= 0)
			return 0;

		if(!is_float(floatval($value)))
			return 0;

		if(is_float(floatval($value)) <= 0)
			return 0;

		return 1;
	}

	public function verificationUtilisateur($user, $mdp)
	{
		$ErreurListe = array('Erreur'=>"Erreur de connexion");

		try
		{
			if($user == '' || $mdp == '')
			{
				echo(json_encode($ErreurListe));
				return;
			}
				$InfoUtilisateur=$this->connexion->query("SELECT COUNT(*) AS NB FROM utilisateur WHERE identifiant = '".$user."' AND mdp = '".$mdp."';");
				$InfoUtilisateur->setFetchMode(PDO::FETCH_OBJ);
				$ligne = $InfoUtilisateur->fetch();
				if($ligne->NB == 0)
					return 0;				
				return 1;
		}
		catch(Exception $e){
			echo(json_encode($ErreurListe));	
		}	
	}

	public function ajouterVente($quantite, $idFromage)
	{
		$ErreurListe = array('Erreur'=>"Erreur");
  		$SuccesListe = array('Succes'=>"Succes");

		if($quantite == '' || $idFromage == '')
		{
			echo(json_encode(array('Erreur'=>htmlentities("Paramètres nuls"))));
		}

		else
		{
				$date = date("Y-m-d");
				try{
					$Ajout="INSERT INTO vente(idFromage, dateVente, quantite) VALUES ('".$idFromage."','".$date."','".$quantite."');";
					$transaction="UPDATE fromage SET stock = stock - ".$quantite." WHERE fromage.idFromage ='".$idFromage."'";

					$this->connexion->beginTransaction();
					$this->connexion->exec($Ajout);
					$this->connexion->exec($transaction);
					if($this->connexion->commit())
						echo(json_encode($SuccesListe));
					else
						echo(json_encode(array('Erreur'=>htmlentities("Erreur lors de la requête"))));
				}
				catch(Exception $e){
					$this->connexion->rollBack();
					echo(json_encode($ErreurListe));	
				}
		}


	}

	public function ajouterRetrait($quantite, $idFromage, $raison)
	{
		$ErreurListe = array('Erreur'=>"Erreur");
  		$SuccesListe = array('Succes'=>"Succes");

		if($quantite == '' || $idFromage == '' || $raison == '')
		{
			echo(json_encode(array('Erreur'=>htmlentities("Paramètres nuls"))));
		}
		else
		{
				try{
				$date = date("Y-m-d");

				$Ajout="INSERT INTO retrait(idFromage, raison, quantite, dateRetrait) VALUES ('".$idFromage."','".$raison."','".$quantite."','".$date."');";
				$transaction="UPDATE fromage SET stock = stock - ".$quantite." WHERE fromage.idFromage ='".$idFromage."'";

				$this->connexion->beginTransaction();
				$this->connexion->exec($Ajout);
				$this->connexion->exec($transaction);
				if($this->connexion->commit())
					echo(json_encode($SuccesListe));
				else
					echo(json_encode(array('Erreur'=>htmlentities("Erreur lors de la requête"))));
				}
				catch(Exception $e){
					$this->connexion->rollBack();
					echo(json_encode($ErreurListe));	
				}				
		}
	}

	public function ajouterFromage($dAffinage, $nomFromage, $photo, $prix, $stock, $unite)
	{
		$ErreurListe = array('Erreur'=>"Erreur");
  		$SuccesListe = array('Succes'=>"Succes");

		if($dAffinage == '' || $nomFromage == '' || $photo == '' || $prix == '' || $stock == '' || $unite == '' )
		{
			echo(json_encode(array('Erreur'=>htmlentities("Paramètres nuls"))));
		}
		else
		{
			try
			{
				$InfoUtilisateur=$this->connexion->query("SELECT COUNT(*) AS NB FROM Fromage WHERE nomFromage = '".$nomFromage."';");
				$InfoUtilisateur->setFetchMode(PDO::FETCH_OBJ);
				$ligne = $InfoUtilisateur->fetch();
				if($ligne->NB != 0)
					echo(json_encode(array('Erreur'=>htmlentities("Ce fromage existe déjà"))));			
				else
				{

					$Ajout="INSERT INTO fromage(nomFromage, dAffinage, photo, prix, stock, unite) VALUES ('".$nomFromage."','".$dAffinage."','".$photo."', '".$prix."', '".$quantite."', '".$unite."');";
					if($this->connexion->exec($Ajout))
						echo(json_encode($SuccesListe));
					else
						echo(json_encode(array('Erreur'=>htmlentities("Erreur lors de la requête"))));
				}
			}
			catch(Exception $e){
						echo(json_encode($ErreurListe));	
			}					
		}
	}

	public function ajouterLot($dAffinage, $idFromage, $quantite)
	{
		$ErreurListe = array('Erreur'=>"Erreur");
  		$SuccesListe = array('Succes'=>"Succes");

		if($dAffinage == '' || $idFromage == '' || $quantite == '')
		{
			echo(json_encode(array('Erreur'=>htmlentities("Paramètres nuls"))));
		}
		else
		{
				try
				{

					$dateCreation = date("Y-m-d");
					$numLot = date("d")."-".date("m");

					$Ajout="INSERT INTO affinage(idFromage, dAffinage, quantite, dateCreation, numLot) VALUES ('".$idFromage."', '".$dAffinage."', '".$quantite."', '".$dateCreation."', '".$numLot."');";

					if($this->connexion->exec($Ajout))
						echo json_encode($SuccesListe);
					else
						echo(json_encode(array('Erreur'=>htmlentities("Erreur lors de la requête"))));
				}
				catch(Exception $e){
					echo(json_encode($ErreurListe));	
				}
		}
	}

	public function ajouterClient($raisonSociale, $nom, $prenom, $mail, $portable, $fixe, $ville, $adresse, $cpt_adresse)
	{
		$ErreurListe = array('Erreur'=>"Erreur");
  		$SuccesListe = array('Succes'=>"Succes");

		if($raisonSociale == '' || $nom == '' || $prenom == '' || $ville == '' || $adresse == '')
		{
			echo(json_encode(array('Erreur'=>htmlentities("Paramètres nuls"))));
		}
		else{
				try{
					$InfoUtilisateur=$this->connexion->query("SELECT COUNT(*) AS NB FROM client WHERE raisonSociale = '".$raisonSociale."';");
					$InfoUtilisateur->setFetchMode(PDO::FETCH_OBJ);
					$ligne = $InfoUtilisateur->fetch();
					if($ligne->NB != 0)
						echo(json_encode(array('Erreur'=>htmlentities("Ce client existe déjà"))));	
					else
					{
						$Ajout="INSERT INTO client(raisonSociale, nom, prenom, mail, portable, fixe, ville, adresse, cpt_adresse) VALUES ('".$raisonSociale."','".$nom."','".$prenom."','".$mail."','".$portable."','".$fixe."','".$ville."','".$adresse."','".$cpt_adresse."');";
						if($this->connexion->exec($Ajout))
							echo(json_encode($SuccesListe));
						else
							echo(json_encode(array('Erreur'=>htmlentities("Erreur lors de la requête"))));						
					}


				}
				catch(Exception $e){
					echo(json_encode($ErreurListe));	
				}					
		}
	}

	public function ajouterCommande($nb, $idClient, $tabFromage, $tabQuantite, $dateLivraison)
	{

		$ErreurListe = array('Erreur'=>"Erreur");
  		$SuccesListe = array('Succes'=>"Succes");
		
		if($idClient == '' || $tabFromage[$nb] == '' || $tabQuantite[$nb] == '' || $dateLivraison == '')
		{
			echo(json_encode(array('Erreur'=>htmlentities("Paramètres nuls"))));
		}
		else
		{
				$date = date("Y-m-d");
				$numCommande = ''.$idClient.'-'.$dateLivraison.'';
				$s = 0;

				for($i = 1 ; $i <= $nb; $i++)
				{
					try{
						$Ajout="INSERT INTO commande (idClient, idFromage, dateSaisie, quantite, numCommande, dateLivraison) VALUES (
							'".$idClient."', 
							'".$tabFromage[$i]."', 
							'".$date."', 
							'".$tabQuantite[$i]."', 
							'".$numCommande."', 
							CAST(FROM_UNIXTIME('".$dateLivraison."') as date))";
							if($this->connexion->exec($Ajout))
								$s++;
							else
								echo(json_encode(array('Erreur'=>htmlentities("Erreur lors de la requête"))));
						}

						catch(Exception $e){
							echo(json_encode($ErreurListe));	
						}			
				}
				echo(json_encode(array('Succes'=>$s)));		
		}
	}

	public function recuperationFromages()
	{
		$ErreurListe = array('Erreur'=>"Erreur");

				try{
				$InfoFromage=$this->connexion->query('SELECT idFromage, nomFromage, dAffinage, photo, prix, stock, unite FROM fromage WHERE visibilite = 1');
				$InfoFromage->setFetchMode(PDO::FETCH_OBJ);
				$cpt = 0;
				//$FromageListe['FromageListe'][$cpt] = 'null';

				while ($ligne = $InfoFromage->fetch()) 
				{
					if($ligne == false)
						echo(json_encode(array('Erreur'=>htmlentities("Erreur lors de la requête"))));
					else
					{
					$FromageJSON = array(
						'idFromage'=>intval($ligne->idFromage),
						'nomFromage'=>$ligne->nomFromage,
						'dAffinage'=>intval($ligne->dAffinage), 
						'photo'=>'http://'.$_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'].PARAM_pathPhoto.($ligne->photo).PARAM_extensionPhoto, 
						'prix'=>floatval($ligne->prix), 
						'stock'=>intval($ligne->stock), 
						'unite'=>$ligne->unite);
					$FromageListe['FromageListe'][$cpt] = $FromageJSON;
					$cpt++;						
					}

				}

					if ($cpt == 0) 
						echo(json_encode(array('Vide'=>htmlentities("Il n'y a pas de fromage"))));
					
					else 
						echo(json_encode($FromageListe));

				}

				catch(Exception $e){
					echo(json_encode($ErreurListe));	
				}			
	}
	
	public function recuperationPhotosFromage()
	{
		$ErreurListe = array('Erreur'=>"Erreur");

				try{
				$InfoPhoto=$this->connexion->query('SELECT nomFromage, photo FROM fromage WHERE visibilite = 1');
				$InfoPhoto->setFetchMode(PDO::FETCH_OBJ);
				$cpt = 0;

				while ($ligne = $InfoPhoto->fetch()) 
				{
					if($ligne == false)
						echo(json_encode(array('Erreur'=>htmlentities("Erreur lors de la requête"))));
					else
					{
					$PhotoJSON = array(
						'nomFromage'=>$ligne->nomFromage, 
						'photo'=>$ligne->photo);
					$PhotoListe['PhotoListe'][$cpt] = $PhotoJSON;
					$cpt++;						
					}

				}

					if ($cpt == 0) 
						echo(json_encode(array('Vide'=>htmlentities("Il n'y a pas de photos"))));
					
					else 
						echo(json_encode($PhotoListe));

				}

				catch(Exception $e){
					echo(json_encode($ErreurListe));	
				}			
	}

	public function recuperationCommandesParIdFromage($idFromage)
	{
		$ErreurListe = array('Erreur'=>"Erreur");

		if($idFromage == '')
		{
			echo(json_encode(array('Erreur'=>"Paramètre nul")));
		}
		else{

				try{
				$InfoFromageCommande=$this->connexion->query("SELECT fromage.nomFromage, commande.numCommande, fromage.dAffinage, commande.dateLivraison, commande.quantite, client.raisonSociale FROM fromage, commande, client WHERE commande.idFromage = '".$idFromage."' AND fromage.idFromage = '".$idFromage."' AND commande.idClient = client.idClient");

				$InfoFromageCommande->setFetchMode(PDO::FETCH_OBJ);
				$cpt = 0;
				$FromageCommandeListe['FromageCommandeListe'][$cpt] = 'null';

				while ($ligne = $InfoFromageCommande->fetch()) 
				{
					if($ligne == false)
						echo(json_encode(array('Erreur'=>htmlentities("Erreur lors de la requête"))));
					else
					{
					$FromageCommandeJSON = array(
						'numCommande'=>$ligne->numCommande,
						'raisonSociale'=>$ligne->raisonSociale,
						'nomFromage'=>$ligne->nomFromage,
						'dAffinage'=>intval($ligne->dAffinage), 
						'dateLivraison'=>("/Date(".strtotime($ligne->dateLivraison)."000)/"), 
						'quantite'=>intval($ligne->quantite));
					$FromageCommandeListe['FromageCommandeListe'][$cpt] = $FromageCommandeJSON;
					$cpt++;						
					}

				}

					if ($cpt == 0) 
						echo(json_encode(array('Vide'=>htmlentities("Il n'y a pas de commande pour ce fromage"))));
					
					else 
						echo json_encode($FromageCommandeListe);
				}

				catch(Exception $e){
					echo(json_encode($ErreurListe));	
				}			
		}
	}

	public function recuperationCommandesParDate($dateDebut, $dateFin)
	{
		$ErreurListe = array('Erreur'=>"Erreur");

		if($dateDebut == '' || $dateFin == '')
		{
			echo(json_encode(array('Erreur'=>htmlentities("Paramètres nuls"))));
		}
		else{

				try{
				$InfoFromageCommande=$this->connexion->query("SELECT fromage.nomFromage, commande.numCommande, commande.dateLivraison, commande.quantite, client.raisonSociale FROM fromage, commande, client WHERE commande.dateLivraison BETWEEN CAST(FROM_UNIXTIME('".$dateDebut."') as date) AND CAST(FROM_UNIXTIME('".$dateFin."') as date) AND commande.idFromage = fromage.idFromage AND commande.idClient = client.idClient");

				$InfoFromageCommande->setFetchMode(PDO::FETCH_OBJ);
				$cpt = 0;
				$FromageCommandeListe['FromageCommandeListe'][$cpt] = 'null';

				while ($ligne = $InfoFromageCommande->fetch()) 
				{
					if($ligne == false)
						echo(json_encode(array('Erreur'=>htmlentities("Erreur lors de la requête"))));
					else
					{
					$FromageCommandeJSON = array(
						'numCommande'=>$ligne->numCommande,
						'raisonSociale'=>$ligne->raisonSociale,
						'nomFromage'=>$ligne->nomFromage,
						'dateLivraison'=>("/Date(".strtotime($ligne->dateLivraison)."000)/"), 
						'quantite'=>intval($ligne->quantite));
					$FromageCommandeListe['FromageCommandeListe'][$cpt] = $FromageCommandeJSON;
					$cpt++;						
					}

				}

					if ($cpt == 0) 
						echo(json_encode(array('Vide'=>htmlentities("Il n'y a pas de commandes entre ces dates"))));
					
					else 
						echo json_encode($FromageCommandeListe);
				}

				catch(Exception $e){
					echo(json_encode($ErreurListe));	
				}			
			}
	}

	public function recuperationLotParIdFromage($idFromage)
	{
		$ErreurListe = array('Erreur'=>"Erreur");

		if($idFromage == '')
		{
			echo(json_encode(array('Erreur'=>htmlentities("Paramètres nuls"))));
		}
		else{
				try{
				$InfoFromageLot=$this->connexion->query("SELECT fromage.nomFromage, affinage.numLot, affinage.dAffinage, fromage.stock, affinage.quantite FROM fromage, affinage WHERE affinage.idFromage = '".$idFromage."' AND fromage.idFromage = '".$idFromage."' AND fromage.visibilite = 1");

				$InfoFromageLot->setFetchMode(PDO::FETCH_OBJ);
				$cpt=0;
				//$FromageLotListe['FromageLotListe'] = 'null';

				while($ligne = $InfoFromageLot->fetch())					
				{
					if($ligne == false)
						echo(json_encode(array('Erreur'=>htmlentities("Erreur lors de la requête"))));
					else
					{
						$FromageLotJSON = array('numLot'=>$ligne->numLot,'nomFromage'=>$ligne->nomFromage,'dAffinage'=>("/Date(".strtotime($ligne->dAffinage)."000)/"), 'quantiteStock'=>intval($ligne->stock), 'quantiteAffinage'=>intval($ligne->quantite));
						$FromageLotListe['FromageLotListe'] = $FromageLotJSON;
						$cpt++;
					}
				}
				if ($cpt == 0) 
						echo(json_encode(array('Vide'=>htmlentities("Il n'y a pas de lots pour ce fromage"))));
					
					else 
						echo json_encode($FromageLotListe);

				}

				catch(Exception $e){
					echo(json_encode($ErreurListe));	
				}			
		}
	}

	public function recuperationFromageParID($idFromage)
	{
		$ErreurListe = array('Erreur'=>"Erreur");

		if($idFromage == '')
		{
			echo(json_encode(array('Erreur'=>htmlentities("Paramètres nuls"))));
		}
		else
		{
				try
				{
					$InfoFromage=$this->connexion->query('SELECT nomFromage, dAffinage, photo, prix, stock, unite FROM fromage WHERE idFromage ='.$idFromage.' AND visibilite = 1');

					$InfoFromage->setFetchMode(PDO::FETCH_OBJ);

					$ligne = $InfoFromage->fetch();
					if($ligne == false)
						echo(json_encode(array('Erreur'=>htmlentities("Il n'y a pas de fromage pour cet identifiant"))));
					else
					{
					$FromageJSON = array('nomFromage'=>$ligne->nomFromage,'dAffinage'=>intval($ligne->dAffinage), 'photo'=>$ligne->photo, 'prix'=>$ligne->prix, 'stock'=>intval($ligne->stock), 'unite'=>$ligne->unite);

						$FromageListe['FromageListe'] = $FromageJSON;
						echo json_encode($FromageListe);
					}		
							
				}

				catch(Exception $e){
					echo(json_encode($ErreurListe));	
				}			
		}
	}

	public function recuperationClients()
	{
		$ErreurListe = array('Erreur'=>"Erreur");

				try{
				$InfoClient=$this->connexion->query("SELECT idClient, raisonSociale, nom, prenom, mail, portable, fixe, ville, adresse FROM client");
				$InfoClient->setFetchMode(PDO::FETCH_OBJ);
				$cpt = 0;
				$ClientListe['ClientListe'][$cpt] = 'null';

				while ($ligne = $InfoClient->fetch()) {
					if($ligne == false)
						echo(json_encode(array('Erreur'=>htmlentities("Erreur lors de la requête"))));
					else
					{
					$ClientJSON = array('idClient'=>intval($ligne->idClient),'raisonSociale'=>$ligne->raisonSociale,'nom'=>$ligne->nom, 'prenom'=>$ligne->prenom, 'mail'=>$ligne->mail, 'portable'=>$ligne->portable, 'fixe'=>$ligne->fixe, 'ville'=>$ligne->ville, 'adresse'=>$ligne->adresse);
					$ClientListe['ClientListe'][$cpt] = $ClientJSON;
					$cpt++;						
					}

				}

					if ($cpt == 0)
						echo(json_encode(array('Vide'=>htmlentities("Il n'y a pas de clients"))));
					
					else 
						echo json_encode($ClientListe);
				}
				catch(Exception $e){
					echo(json_encode($ErreurListe));	
				}				
	}

	public function recuperationStocks()
	{
		$ErreurListe = array('Erreur'=>"Erreur");

		try
		{
			$InfoStock=$this->connexion->query("SELECT nomFromage, stock FROM fromage WHERE visibilite = 1");
			$InfoStock->setFetchMode(PDO::FETCH_OBJ);
			$cpt = 0;
			$StockListe['StockListe'][$cpt] = 'null';

			while ($ligne = $InfoStock->fetch()) 
			{
				if($ligne == false)
						echo(json_encode(array('Erreur'=>htmlentities("Erreur lors de la requête"))));
				else
				{
				$StockJSON = array('nomFromage'=>$ligne->nomFromage, 'stock'=>intval($ligne->stock));
				$StockListe['StockListe'][$cpt] = $StockJSON;
				$cpt++;					
				}

			}

				if ($cpt == 0) 
					echo(json_encode(array('Vide'=>htmlentities("Il n'y a pas de stock"))));
					
				else 
					echo json_encode($StockListe);
			}
			catch(Exception $e){
				echo(json_encode($ErreurListe));	
			}	
	}

	public function recuperationLots()
	{
		$ErreurListe = array('Erreur'=>"Erreur");

				try{
				$InfoLot=$this->connexion->query("SELECT idLot, affinage.idFromage, affinage.quantite, affinage.dAffinage, dateCreation, nomFromage, dateMiseEnStock FROM affinage, fromage WHERE dateCreation + affinage.dAffinage > current_date() AND affinage.idFromage = fromage.idFromage");
				$InfoLot->setFetchMode(PDO::FETCH_OBJ);
				$cpt = 0;
				$LotListe['LotListe'][$cpt] = 'null';

				while ($ligne = $InfoLot->fetch()) {
					if($ligne == false)
						echo(json_encode(array('Erreur'=>htmlentities("Erreur lors de la requête"))));
					else
					{
					$LotJSON = array('nomFromage'=>$ligne->nomFromage, 'idLot'=>intval($ligne->idLot),'idFromage'=>intval($ligne->idFromage), 'quantite'=>intval($ligne->quantite), 'dAffinage'=>("/Date(".strtotime($ligne->dAffinage)."000)/"), 'dateCreation'=>("/Date(".strtotime($ligne->dateCreation)."000)/"), 'dateMiseEnStock'=>("/Date(".strtotime($ligne->dateMiseEnStock)."000)/"));
					$LotListe['LotListe'][$cpt] = $LotJSON;
					$cpt++;						
					}

				}

					if ($cpt == 0) 
						echo(json_encode(array('Vide'=>htmlentities("Il n'y a pas de lot  à venir"))));
					else 
						echo json_encode($LotListe);
				}

				catch(Exception $e){
					echo(json_encode($ErreurListe));	
				}				
	}

	public function recuperationCommandesParClient($idClient)
	{
		$ErreurListe = array('Erreur'=>"Erreur");

		if($idClient == '')
		{
			echo(json_encode(array('Erreur'=>htmlentities("Paramètres nuls"))));
		}
		else{
				try{
				$InfoCommande=$this->connexion->query('SELECT idCommande, commande.idClient, commande.idFromage, dateSaisie, commande.quantite, numCommande, dateLivraison, client.raisonSociale, fromage.nomFromage FROM commande, client, fromage WHERE commande.idClient ='.$idClient.' AND commande.idClient = client.idClient AND commande.idFromage = fromage.idFromage');
				$InfoCommande->setFetchMode(PDO::FETCH_OBJ);
				$cpt = 0;
				$CommandeListe['CommandeListe'][$cpt] = 'null';

				while ($ligne = $InfoCommande->fetch()) {
					if($ligne == false)
						echo(json_encode(array('Erreur'=>"Erreur lors de la requête")));
					else
					{
					$CommandeJSON = array(
						'idCommande'=>intval($ligne->idCommande),
						'nomFromage'=>$ligne->nomFromage,
						'raisonSociale'=>$ligne->raisonSociale,
						'idClient'=>intval($ligne->idClient), 
						'idFromage'=>intval($ligne->idFromage), 
						'dateSaisie'=>("/Date(".strtotime($ligne->dateSaisie)."000)/"), 
						'quantite'=>intval($ligne->quantite),
						'numCommande'=>$ligne->numCommande,
						'dateLivraison'=>("/Date(".strtotime($ligne->dateLivraison)."000)/"));
					$CommandeListe['CommandeListe'][$cpt] = $CommandeJSON;
					$cpt++;
					}

				}

					if ($cpt == 0) 
						echo(json_encode(array('Vide'=>htmlentities("Il n'y a pas de commandes pour ce client."))));
					
					else 
						echo json_encode($CommandeListe);
					
							
					$InfoCommande->closeCursor();
				}

				catch(Exception $e){
					echo(json_encode($ErreurListe));	
				}					
		}
	}

	public function recuperationCommandes()
	{
		$ErreurListe = array('Erreur'=>"Erreur");

				try{
				$dateTimeJour = Date('Y-m-d h:i:s');
				$InfoCommande=$this->connexion->query('SELECT idCommande, commande.idClient, idFromage, dateSaisie, quantite, numCommande, dateLivraison, raisonSociale FROM commande, client WHERE dateLivraison >"'.$dateTimeJour.'" AND commande.idClient = client.idClient');
				$InfoCommande->setFetchMode(PDO::FETCH_OBJ);
				$cpt = 0;
				$CommandeListe['CommandeListe'][$cpt] = 'null';

				while ($ligne = $InfoCommande->fetch()) {
					$CommandeJSON = array(
						'idCommande'=>intval($ligne->idCommande), 
						'raisonSociale'=>$ligne->raisonSociale,
						'idClient'=>intval($ligne->idClient), 
						'idFromage'=>intval($ligne->idFromage), 
						'dateSaisie'=>("/Date(".strtotime($ligne->dateSaisie)."000)/"), 
						'quantite'=>intval($ligne->quantite),
						'numCommande'=>$ligne->numCommande,
						'dateLivraison'=>("/Date(".strtotime($ligne->dateLivraison)."000)/"));

					$CommandeListe['CommandeListe'][$cpt] = $CommandeJSON;
					$cpt++;
				}

					if ($cpt == 0) 
						echo(json_encode(array('Vide'=>"Il n'y a pas de commandes")));
					
					else 
						echo json_encode($CommandeListe);
					
							
					$InfoCommande->closeCursor();
				}

				catch(Exception $e){
					echo(json_encode($ErreurListe));	
				}					
	}

	public function modifierFromage($idFromage, $nomFromage, $dAffinage, $photo, $prix, $unite, $visibilite)
	{
		$ErreurListe = array('Erreur'=>"Erreur");
  		$SuccesListe = array('Succes'=>"Succes");

		if($idFromage == '' || $nomFromage == '' || $dAffinage == '' || $photo == '' || $prix == '' || $unite == '')
		{
			echo(json_encode(array('Erreur'=>htmlentities("Paramètres nuls"))));
		}
		else
		{
				try
				{
					$Modifier="UPDATE fromage SET nomFromage = '".$nomFromage."', dAffinage = '".$dAffinage."', photo = '".$photo."', prix = '".$prix."', unite = '".$unite."', visibilite = '".$visibilite."' WHERE idFromage = '".$idFromage."';";

					if($this->connexion->exec($Modifier))
						echo(json_encode($SuccesListe));
					else
						echo(json_encode(array('Erreur'=>htmlentities("Erreur lors de la requête"))));

				}
				catch(Exception $e){
					echo(json_encode($ErreurListe));	
				}				
		}
	}

	public function modifierClient($idClient, $raisonSociale, $nom, $prenom, $mail, $portable, $fixe, $ville, $adresse)
	{
		$ErreurListe = array('Erreur'=>"Erreur");
  		$SuccesListe = array('Succes'=>"Succes");

		if($idClient == '' || $raisonSociale == '' || $nom == '' || $prenom == '' || $ville == '' || $adresse == '')
		{
			echo(json_encode(array('Erreur'=>htmlentities("Paramètres nuls"))));
		}
		else{
				try
				{

					$InfoClient="UPDATE client SET idClient = '".$idClient."', raisonSociale = '".$raisonSociale."', nom = '".$nom."', prenom = '".$prenom."', mail = '".$mail."', portable = '".$portable."', fixe = '".$fixe."',ville = '".$ville."', adresse ='".$adresse."' WHERE idClient = '".$idClient."';";

					if($this->connexion->exec($InfoClient))
						echo(json_encode($SuccesListe));
					else
						echo(json_encode(array('Erreur'=>htmlentities("Erreur lors de la requête"))));

				}
				catch(Exception $e){
					echo(json_encode($ErreurListe));	
				}			
		}
	}

	public function modifierCommande($idCommande, $idClient, $idFromage, $quantite, $dateLivraison)
	{
		$ErreurListe = array('Erreur'=>"Erreur");
  		$SuccesListe = array('Succes'=>"Succes");

		if($idClient == '' || $idFromage == '' || $quantite == '' || $dateLivraison == '')
		{
			echo(json_encode(array('Erreur'=>htmlentities("Paramètres nuls"))));
		}
		else
		{
				$date = strtotime(date("Y-m-d"));
				$numCommande = ''.$idClient.'-'.$dateLivraison.'';

					try{
					$Modifier="UPDATE commande SET idFromage ='".$idFromage."',
						idClient = '".$idClient."', 
						quantite = '".$quantite."', 
						dateSaisie = CAST(FROM_UNIXTIME('".$date."') as date),
						 dateLivraison = CAST(FROM_UNIXTIME('".$dateLivraison."') as date), 
						 numCommande = '".$numCommande."' WHERE idCommande = '".$idCommande."';";

						if($this->connexion->exec($Modifier))
							echo(json_encode($SuccesListe));
						else
						echo(json_encode(array('Erreur'=>htmlentities("Erreur lors de la requête"))));
					}
					catch(Exception $e){
						echo(json_encode($ErreurListe));
					}						
		}
	}

	public function modifierLot($idLot, $quantite, $idFromage, $dAffinage)
	{
		$ErreurListe = array('Erreur'=>"Erreur");
  		$SuccesListe = array('Succes'=>"Succes");

		if($idLot == '' || $quantite == '' || $idFromage == '' || $dAffinage == '')
		{
			echo(json_encode(array('Erreur'=>htmlentities("Paramètres nuls"))));
		}
		else
		{
				try
				{

					$Modifier="UPDATE affinage SET 
					idFromage = '".$idFromage."', 
					quantite = '".$quantite."', 
					dAffinage = CAST(FROM_UNIXTIME('".$dAffinage."') as date) 
					WHERE idLot = '".$idLot."';";
					if($this->connexion->exec($Modifier))
						echo(json_encode($SuccesListe));
					else
						echo(json_encode(array('Erreur'=>htmlentities("Erreur lors de la requête"))));

				}
				catch(Exception $e){
					echo(json_encode($ErreurListe));	
				}				
		}
	}

	public function supprimerCommande($numCommande, $user, $mdp)
	{
		$ErreurListe = array('Erreur'=>"Erreur");
  		$SuccesListe = array('Succes'=>"Succes");

		if($numCommande == '')
		{
			echo(json_encode(array('Erreur'=>htmlentities("Paramètres nuls"))));
		}
		else
		{
				try
				{

					$Supprimer="DELETE FROM commande WHERE numCommande = '".$numCommande."';";
					echo($InfoStock);
					if($this->connexion->exec($Supprimer))
						echo(json_encode($SuccesListe));
					else
						echo (json_encode(array('Erreur'=>"Erreur lors de la requête")));

				}
				catch(Exception $e){
					echo(json_encode($ErreurListe));	
				}				
		}
	}
 }
?>
