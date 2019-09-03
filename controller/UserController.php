<?php
/*
 *USER LOGIN, LOGOUT AND PASSWORD CHANGING FUNCTIONS
 *logIN() -> connect
 *logOut() -> disconnect
 *newPass() -> change password
 *newMail() -> change mail
 *newPseudo() -> change pseudo
 *newUser()
*/
class UserController {

	public function logIn($pseudo, $pass) {
		require_once ("model/UserManager.php");
		$connexionManager = new UserManager();
		$count = $connexionManager->count($pseudo);
		if ($count != 0) { //if the asked pseudo is found
			$req = $connexionManager->getUser($pseudo);
			if (empty($_SESSION['pseudo'])) {
				if (password_verify($pass, $req['pass'])) {
					$_SESSION['pseudo'] = $req['pseudo'];
					header('Location: /projet5/index.php?action=backendHome&success=connexion&pseudo=' . $req['pseudo']);
				}
				else {
					header('Location: index.php?action=signin&erreur=identifiant');
				}
			}
			else {
				header('Location: index.php?action=signin&erreur=sessionexist');
			}
		}
		else {
			header('Location: index.php?action=signin&erreur=identifiant');
		}
	}
	public function logOut() {
		// session_start();
		$_SESSION = array();
		session_destroy();
	}

	public function newPass($oldPass, $newPass, $pseudo) {
		require_once ("model/UserManager.php");
		$connexionManager = new UserManager();
		$count = $connexionManager->count($pseudo);
		if ($count != 0) {
			$donnees = $connexionManager->getUser($pseudo);
			if (password_verify($oldPass, $donnees['pass'])) {
				if ($oldPass != $newPass) {
					$hashed_password = password_hash($newPass, PASSWORD_DEFAULT);
					$req = $connexionManager->updateUserPw($hashed_password, $pseudo);
					header('Location: index.php?action=settingsview&success=updatepass');
				}
				else header('Location: index.php?action=settingsview&change=pass&erreur=samepw');
			}
			else header('Location: index.php?action=settingsview&change=pass&erreur=passpseudo');
		}
		else header('Location: index.php?action=settingsview&change=pass&erreur=passpseudo');
	}

	public function newMail($pseudo, $comfirmMail, $newmail, $pass) {
		require_once ("model/UserManager.php");
		$connexionManager = new UserManager();
		$count = $connexionManager->count($pseudo);
		if ($count != 0) {
			$donnees = $connexionManager->getUser($pseudo);
			if (password_verify($pass, $donnees['pass'])) {
				if ($comfirmMail == $newmail) {
					if (preg_match(" /^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/ ", $newmail)) {
						$req = $connexionManager->updateUserMail($newmail, $pseudo);
						UserController::logIn($pseudo, $pass);
						header('Location: index.php?action=settingsview&success=updatemail');
					}
					else header('Location: index.php?action=settingsview&change=mail&erreur=mailbadsyntax');
				}
				else header('Location: index.php?action=settingsview&change=mail&erreur=diffmail');
			}
			else header('Location: index.php?action=settingsview&change=mail&erreur=passpseudo');
		}
		else header('Location: index.php?action=settingsview&change=mail&erreur=passpseudo');
	}

	public function newPseudo($newpseudo, $pseudo, $pass) {
		require_once ("model/UserManager.php");
		$connexionManager = new UserManager();
		$count = $connexionManager->count($pseudo);
		$countNewPseudo = $connexionManager->count($newpseudo);

		if ($count != 0) {
			if ($countNewPseudo == 0) {
				$user = $connexionManager->getUser($pseudo);
				if (password_verify($pass, $user['pass'])) {
					if ($pseudo != $newpseudo) {
						$req = $connexionManager->updateUserPseudo($pseudo, $newpseudo);
						$_SESSION['pseudo'] = $newpseudo;

						header('Location: index.php?action=settingsview&success=updatepseudo');
					}
					else header('Location: index.php?action=settingsview&change=pseudo&erreur=diffpseudo');
				}
				else header('Location: index.php?action=settingsview&change=pseudo&erreur=passpseudo');
				
				}
				else header('Location: index.php?action=settingsview&change=pseudo&erreur=pseudoindb');
		}
		else header('Location: index.php?action=settingsview&change=pseudo&erreur=passpseudo');
	}

	public function newUser($pseudo, $pass, $passTwo, $mail, $imgUrl) {
		require_once ("model/UserManager.php");
		$connexionManager = new UserManager();
		$count = $connexionManager->count($pseudo);
		$mailDispo = $connexionManager->findMail($mail);

		if ($count == 0) { //if pseudo doesnt already exist
			if ($pass == $passTwo) {
				if ($mailDispo == 0) {
					if (preg_match(" /^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/ ", $mail)) {
						$hashed_password = password_hash($pass, PASSWORD_DEFAULT);
						$newUser = $connexionManager->newUser($pseudo, $hashed_password, $mail, $imgUrl);

						UserController::logIn($pseudo, $pass);
					}
					else {
						header('Location: index.php?action=signup&erreur=badmail');
					}
				}
				else {
					header('Location: index.php?action=signup&erreur=mailalreadydatabase');
				}
			}
			else {
				header('Location: index.php?action=signup&erreur=badpass');
			}
		}
		else {
			header('Location: index.php?action=signup&erreur=pseudoindb');
		}
	}
	public function newCat($imageUrl) {
		require_once ("model/UserManager.php");
		$connexionManager = new UserManager();
		$user = $connexionManager->updateCat($_SESSION['pseudo'], $imageUrl);
	}
	public function deleteAccount($pseudo, $pass) {
		require_once ("model/UserManager.php");
		$connexionManager = new UserManager();
		$commentManager = new CommentManager();
		$count = $connexionManager->count($pseudo);

		if ($count != 0) { //if the asked pseudo is found
			$req = $connexionManager->getUser($pseudo);
				if (password_verify($pass, $req['pass'])) {
					$deleteComments = $commentManager->deleteCommentFromOneUser($req['id']);
					$user = $connexionManager->deleteAccount($_SESSION['pseudo']);
					$this->logOut();
					header('Location: index.php?success=bye');
				} else header('Location: index.php?action=settingsview&change=account&erreur=passpseudo');
			} else header('Location: index.php?action=settingsview&change=account&erreur=passpseudo');
	}
	public function AdminDeleteAccount($id) {
		require_once ("model/UserManager.php");
		$connexionManager = new UserManager();
		$commentManager = new CommentManager();
		$deleteComments = $commentManager->deleteCommentFromOneUser($id);
		$user = $connexionManager->deleteAccountWithID($id);

	}
	public function endGameOne($score) {
		require_once ("model/UserManager.php");
		$connexionManager = new UserManager();
		$reponse = $connexionManager->getUser($_SESSION['pseudo']);
		if ($score > $reponse['game_one_bs']) {
			$user = $connexionManager->updateScoreOne($_SESSION['pseudo'], $score, 1);
		}
		else {
			$user = $connexionManager->updateScoreOne($_SESSION['pseudo'], $score, 0);
		}
	}
	public function endGameTwo($score) {
		require_once ("model/UserManager.php");
		$connexionManager = new UserManager();
		$reponse = $connexionManager->getUser($_SESSION['pseudo']);
		if ($score > $reponse['game_two_bs']) {
			$user = $connexionManager->updateScoreTwo($_SESSION['pseudo'], $score, 1);
		}
		else {
			$user = $connexionManager->updateScoreTwo($_SESSION['pseudo'], $score, 0);
		}
	}
}