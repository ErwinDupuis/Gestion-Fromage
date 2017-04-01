<?php
include_once('parametres.php');
include_once('service.php');

$ErreurListe = array('Erreur'=>htmlentities("Paramètres manquants."));

if (!isset($_GET['q']) && !isset($_GET['user']) && !isset($_GET['mdp']))
 	echo(json_encode($ErreurListe));

 else
 {
 	try{
	$Classe = new Service($PARAM_hote, $PARAM_port, $PARAM_nom_bd,$PARAM_utilisateur,$PARAM_mot_passe);

	if($Classe->verificationUtilisateur($_GET['user'], $_GET['mdp']) == 1)
	{
		if(!isset($_GET['q']))
			echo(json_encode(array('Erreur'=>htmlentities("La syntaxe demandée est : ?q=..."))));
		else
		{
			switch($_GET['q'])
			{
				case 'recuperationFromages' : 
					$Classe->recuperationFromages();
					break;

				case 'recuperationPhotosFromage' : 
					$Classe->recuperationPhotosFromage();
					break;	
					
				case 'recuperationCommandesParIdFromage': 
					if (isset($_GET['idFromage']))
					{
						$idFromage = $_GET['idFromage'];
						if($Classe->verificationINT($idFromage) == 1)
							$Classe->recuperationCommandesParIdFromage($idFromage);	
						else
							echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));		
					}
					else
					{
						echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));
					}
					break;

				case 'recuperationCommandesParDate':
					if (isset($_GET['dateDebut']) && isset($_GET['dateFin']))
					{
						$dateDebut = $_GET['dateDebut'];
						$dateFin = $_GET['dateFin'];
						if($Classe->verificationDate($dateDebut) && $Classe->verificationDate($dateFin))
							$Classe->recuperationCommandesParDate($dateDebut, $dateFin);
						else
							echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));			
					}
					else
					{
						echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));
					}
					break;

				case 'recuperationLotParIdFromage' :
					if (isset($_GET['idFromage']))
					{
						$idFromage = $_GET['idFromage'];
						if($Classe->verificationINT($idFromage) == 1)
							$Classe->recuperationLotParIdFromage($idFromage);
						else
							echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));			
					}
					else
					{
						echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));
					}		
					break;

				case 'recuperationFromageParID' :
					if (isset($_GET['idFromage']))
					{
						$idFromage = $_GET['idFromage'];
						if($Classe->verificationINT($idFromage) == 1)
							$Classe->recuperationFromageParID($idFromage);
						else
							echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));			
					}
					else
					{
						echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));
					}
					break;

				case 'recuperationCommandesParClient' :	
					if (isset($_GET['idClient']))
					{	
						$idClient = $_GET['idClient'];
						if($Classe->verificationINT($idClient) == 1)
							$Classe->recuperationCommandesParClient($idClient);	
						else
							echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));
					}
					else
					{
						echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));
					}	
					break;

				case 'recuperationCommandes' :
					$Classe->recuperationCommandes();
					break;

				case 'recuperationClients' :
					$Classe->recuperationClients();
					break;

				case 'recuperationStocks' :
					$Classe->recuperationStocks();
					break;

				case 'recuperationLots' :
					$Classe->recuperationLots();
					break;

				case 'ajouterVente' :
					if (isset($_GET['quantite']) && isset($_GET['idFromage']))
					{
						$quantite = $_GET['quantite'];
						$idFromage = $_GET['idFromage'];
						if($Classe->verificationINT($idFromage) == 1 && $Classe->verificationINT($quantite) == 1)
							$Classe->ajouterVente($quantite, $idFromage);
						else
							echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));	
					}
					else
					{
						echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));
					}
					break;

				case 'ajouterRetrait' :
					if (isset($_GET['quantite']) && isset($_GET['idFromage']) && isset($_GET['raison']))
					{
						$quantite = $_GET['quantite'];
						$idFromage = $_GET['idFromage'];
						$raison = $_GET['raison'];
						if($Classe->verificationINT($idFromage) == 1 && $Classe->verificationINT($quantite) == 1 && is_string($raison))
							$Classe->ajouterRetrait($quantite, $idFromage, $raison);
						else
							echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));	
					}
					else
					{
						echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));
					}
					break;

				case 'ajouterFromage' :
					if (isset($_GET['stock']) && isset($_GET['nomFromage']) && isset($_GET['dAffinage']) && isset($_GET['photo']) && isset($_GET['prix']) && isset($_GET['unite']))
					{
						$stock = $_GET['stock'];
						$nomFromage = $_GET['nomFromage'];
						$dAffinage = $_GET['dAffinage'];
						$photo = $_GET['photo'];
						$prix = $_GET['prix'];
						$unite = $_GET['unite'];
						if(is_string($nomFromage) && $Classe->verificationINT($stock) == 1 && $Classe->verificationINT($dAffinage) == 1 && is_string($photo) && $Classe->verificationFloat($prix) == 1 && is_string($unite))
							$Classe->ajouterFromage($dAffinage, $nomFromage, $photo, $prix, $stock, $unite);	
						else
							echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));	
					}
					else
					{
						echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));
					}		
					break;

				case 'ajouterLot' :
					if (isset($_GET['quantite']) && isset($_GET['idFromage']) && isset($_GET['dAffinage']))
					{
						$quantite = $_GET['quantite'];
						$idFromage = $_GET['idFromage'];
						$dAffinage = $_GET['dAffinage'];
						if($Classe->verificationINT($idFromage) == 1 && $Classe->verificationINT($quantite) == 1 && $Classe->verificationINT($dAffinage) == 1)
							$Classe->ajouterLot($dAffinage, $idFromage, $quantite);
						else
							echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));
					}
					else
					{
						echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));
					}
					break;	

				case 'ajouterClient' :
					if (isset($_GET['raisonSociale']) && isset($_GET['nom']) && isset($_GET['prenom']) && isset($_GET['ville']) && isset($_GET['adresse']) && isset($_GET['cpt_adresse']))
					{
						$raisonSociale = $_GET['raisonSociale'];
						$nom = $_GET['nom'];
						$prenom = $_GET['prenom'];
						$mail = $_GET['mail'];
						$portable = $_GET['portable'];
						$fixe = $_GET['fixe'];
						$ville = $_GET['ville'];
						$adresse = $_GET['adresse'];
						$cpt_adresse = $_GET['cpt_adresse'];
						if(is_string($raisonSociale) && is_string($nom) && is_string($prenom) && is_string($mail) && is_string($portable) && is_string($fixe) && is_string($ville) && is_string($adresse) && is_string($cpt_adresse))
							$Classe->ajouterClient($raisonSociale, $nom, $prenom, $mail, $portable, $fixe, $ville, $adresse, $cpt_adresse);
						else
							echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));
					}
					else
					{
						echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));
					}
					break;

				case 'ajouterCommande' :
					if (isset($_GET['idClient']) && isset($_GET['nb']) && isset($_GET['dateLivraison']))
					{	
						$idClient = $_GET['idClient'];
						$nb = $_GET['nb'];
						$dateLivraison = $_GET['dateLivraison'];
						if($Classe->verificationINT($idClient) == 1 && $Classe->verificationINT($nb) == 1 && $Classe->verificationDate($dateLivraison))
						{
							if($nb != 0)
							{
								for ($i=1; $i <= $nb; $i++) 
								{ 
									$tabFromage[$i] = $_GET['idFromage'.$i];
									$tabQuantite[$i] = $_GET['quantite'.$i];				
								}
								$Classe->ajouterCommande($nb, $idClient, $tabFromage, $tabQuantite, $dateLivraison);				
							}
							else
								echo(json_encode($ErreurListe));
						}
						else
							echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));

						
					}
					else
					{
						echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));
					}
					break;

				case 'modifierFromage' :
					if (isset($_GET['nomFromage']) && isset($_GET['idFromage']) && isset($_GET['dAffinage']) && isset($_GET['photo']) && isset($_GET['prix']) && isset($_GET['unite']) && isset($_GET['visibilite']))
					{
						$nomFromage = addslashes($_GET['nomFromage']);
						$idFromage = $_GET['idFromage'];
						$dAffinage = $_GET['dAffinage'];
						$photo = $_GET['photo'];
						$prix = $_GET['prix'];
						$unite = $_GET['unite'];
						$visibilite = $_GET['visibilite'];
						if(is_string($nomFromage) && $Classe->verificationINT($idFromage) == 1 && $Classe->verificationINT($dAffinage) == 1 && is_string($photo) && $Classe->verificationFloat($prix) == 1 && is_string($unite) && $Classe->verificationINT($visibilite) == 1)
							$Classe->modifierFromage($idFromage, $nomFromage, $dAffinage, $photo, $prix, $unite, $visibilite);	
						else
							echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));	
					}	
					else
					{
						echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));
					}
					break;

				case 'modifierClient' :
					if (isset($_GET['idClient']) && isset($_GET['raisonSociale']) && isset($_GET['nom']) && isset($_GET['prenom']) && isset($_GET['ville']) && isset($_GET['adresse']))
					{
						$idClient = $_GET['idClient'];
						$raisonSociale = addslashes($_GET['raisonSociale']);
						$nom = addslashes($_GET['nom']);
						$prenom = addslashes($_GET['prenom']);
						$mail = $_GET['mail'];
						$portable = $_GET['portable'];
						$fixe = $_GET['fixe'];
						$ville = $_GET['ville'];
						$adresse = $_GET['adresse'];
						if($Classe->verificationINT($idClient) == 1 && is_string($raisonSociale) && is_string($nom) && is_string($prenom) && is_string($mail) && is_string($portable) && is_string($fixe) && is_string($ville) && is_string($adresse))
							$Classe->modifierClient($idClient, $raisonSociale, $nom, $prenom, $mail, $portable, $fixe, $ville, $adresse);
						else
							echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));	
					}
					else
					{
						echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));
					}	
					break;

				case 'modifierCommande' :
					if (isset($_GET['dateLivraison']) && isset($_GET['idClient']) && isset($_GET['idCommande']))
					{	
						$idClient = $_GET['idClient'];
						$idCommande = $_GET['idCommande'];
						$dateLivraison = $_GET['dateLivraison'];
						$idFromage = $_GET['idFromage'];
						$quantite = $_GET['quantite'];
						if($Classe->verificationINT($idClient) == 1 && $Classe->verificationINT($idCommande) == 1 && $Classe->verificationDate($dateLivraison) && $Classe->verificationINT($idFromage) == 1 && $Classe->verificationINT($quantite) == 1)
							$Classe->modifierCommande($idCommande, $idClient, $idFromage, $quantite, $dateLivraison);
						else
							echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));

					}	
					else
					{
						echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));
					}
					break;

				case 'modifierStock' :
					if (isset($_GET['idStock']) && isset($_GET['quantite']) && isset($_GET['idFromage']))
					{
						$quantite = $_GET['quantite'];
						$idFromage = $_GET['idFromage'];
						$dAffinage = $_GET['idStock'];
						if($Classe->verificationINT($idStock) == 1 && $Classe->verificationINT($idFromage) == 1 && $Classe->verificationINT($quantite) == 1)
							$Classe->modifierStock($idStock, $quantite, $idFromage);
						else
							echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));	
					}
					else
					{
						echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));
					}
					break;

				case 'modifierLot' :
					if (isset($_GET['idLot']) && isset($_GET['quantite']) && isset($_GET['idFromage']) && isset($_GET['dAffinage']))
					{
						$idLot = $_GET['idLot'];
						$quantite = $_GET['quantite'];
						$idFromage = $_GET['idFromage'];
						$dAffinage = $_GET['dAffinage'];
						if($Classe->verificationINT($idLot) == 1 && $Classe->verificationINT($dAffinage) == 1 && $Classe->verificationINT($idFromage) == 1 && $Classe->verificationINT($quantite) == 1)
							$Classe->modifierLot($idLot, $quantite, $idFromage, $dAffinage);
						else
							echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));	
					}
					else
					{
						echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));
					}
					break;

				case 'supprimerCommande' :
					if (isset($_GET['numCommande']))
					{
						$numCommande = $_GET['numCommande'];
						if(is_string($numCommande))
							$Classe->supprimerCommande($numCommande);
						else
							echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));
					}
					else
					{
						echo(json_encode(array('Erreur'=>htmlentities("Erreur dans les parametres"))));
					}
					break;

				default ;
					echo(json_encode(array('Erreur'=>"Erreur dans la syntax de la requete")));
				break;

			}			
		}	
		
	}
	else
	{
		echo(json_encode(array('Erreur'=>"Erreur dans les identifiants")));
	}	
}
catch(Exception $ex){
	$retour = array("Erreur"=>$ex->getMessage());
	echo json_encode ($retour);
}
 }
?>
