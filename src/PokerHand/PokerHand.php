<?php

namespace PokerHand;

class PokerHand{

    public function __construct($hand){
    	
    	//Split hand string into cards, delimit by space
		$cards = explode(" ", $hand);
		$this->cards = $cards;
		$this->faceValues = "";
		$this->cardSuits = "";

		//temp storage arrays
		$faceValuesArr = array();
		$cardSuitsArr = array();

		//Split card into value and suit properties
		foreach($cards as $value){
			$cardValues = preg_split('/([schdSCHD])/', $value, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY); //delimit by suit, save delimiter
			
			//Replace royalty cards with a number designation for simple sorting
			if($cardValues[0]== 'A'){
				$faceValuesArr[] = 14;
			}elseif($cardValues[0]== 'K'){
				$faceValuesArr[] = 13;
			}elseif($cardValues[0]== 'Q'){
				$faceValuesArr[] = 12;
			}elseif($cardValues[0]== 'J'){
				$faceValuesArr[] = 11;
			}else{
				$faceValuesArr[] = $cardValues[0];
			}
			$cardSuitsArr[] = strtolower($cardValues[1]);

			sort($faceValuesArr); //sort values asc
			$this->faceValues = $faceValuesArr;
			$this->cardSuits = $cardSuitsArr;
		}
    }


    //Evaluates Poker Hand Rank
    public function getRank(){

    	$hand = $this->cards;
    	$suits = $this->cardSuits;
    	$faceValues = $this->faceValues;
    	
    	$numCards = sizeof($hand);
    	if($numCards < 5){ //make sure there a 5 cards
    		return "not enough cards to play 5-card poker\n";
    	} //TODO: also check to make sure suits are 1 of 4 accepted letters + additional cases and assumptions

    	//Boolean Flags
    	$allFaceCards = false;
    	$isFlush = false;
    	$isStraight = false;
    	$isStraightFlush = false;
    	$isFourOfAKind = false;
    	$isFullHouse = false;
    	$isThreeOfAKind = false;
    	$isTwoPair = false;
    	$isPair = false;
    	$highCard = "";

		$isFlush = $this->checkFlush($suits); //check for flush
		$isStraight = $this->checkStraight($faceValues); //check for straight
		$highCard = $this->getHighCard($faceValues); //get high card
		$sets = $this->getSetsOfAKind($faceValues); //check for sets
		$allFaceCards = $this->allFaceCards($faceValues); //check for unique face cards

		//Evaluate Hand based on bool flags
		if($isFlush == true && $allFaceCards == true){ echo "\nRoyal Flush\n"; return "Royal Flush";} //Royal Flush
		elseif($isStraight == true && $isFlush == true){echo "\nStraight Flush\n"; return "Straight Flush";} //Straight Flush
		elseif($isFlush == true){ echo "\nFlush\n"; return "Flush";} //Flush
		elseif($isStraight == true){echo "\nStraight\n"; return "Straight";} //Straight
		/*elseif($allFaceCards == true){
			echo "\nCongrats, you have achieved the mythic Royal Straight\n";
		}*/
		else{
			//Check for pairs
			if($sets['four']){
				echo "\nFour of a Kind: ";
				echo $sets['four'];
				echo "s\n";
				return "Four of a Kind";
			}
			elseif($sets['three'] && is_array($sets['pair'])){
				if(sizeof($sets['pair']) == 1){
					echo "\nFull House: (3) ";
					echo $sets['three'];
					echo "s and (2) ";
					echo $sets['pair'][0];
					echo "s\n";
					return "Full House";
				}
				else{
					echo "\nTrips: ";
					echo $sets['three'];
					echo "s\n";
					return "Three of a Kind";
				}
			}
			elseif(is_array($sets['pair'])){
				if(sizeof($sets['pair']) == 1){
					echo "\nPair of: ";
					echo $sets['pair'][0];
					echo "s\n";
					return "One Pair";
				}
				elseif(sizeof($sets['pair']) == 2){
					echo "\nTwo Pairs: ";
					echo $sets['pair'][0];
					echo " and ";
					echo $sets['pair'][1];
					echo "s\n";
					return "Two Pair";
				}
				else{
					echo "\nHigh Card is: ";
					echo $highCard;
					return "High Card";
				}
			}
		}
    }

    //Check for Flush
    public function checkFlush(array $suits){
		$allValuesAreTheSame = (count(array_unique($suits)) === 1); //If there is only one unique suit, must be a flush
		if($allValuesAreTheSame === true){
			return true;
		}
    }

    //Check for Straight
    public function checkStraight(array $faceValues){
		$last = 0;
		$count = 0;
		$wheel = false;
		$values = array();
		foreach ($faceValues as $faceValue){
			$values[] = $faceValue;
		    if($faceValue == $last) {
		        continue;
		    }else if ($faceValue == ++$last){
		        $count++;
		    }else{
		        if($last == 6) $wheel = true;
		        $count = 1;
		        //echo $last;
		        $last = $faceValue;
		    }
		    //Ace wraparound condition
		    if($count == 5 || ($faceValue == 14 && $wheel && $values[0]==2)){ //if wheel is true, A is last and 2 is first, then wrap-around straight
		        $straight = range($last - 4, $last);
				return true;
		    }
		}	
    }

    //Check for presence of all face cards
    public function allFaceCards(array $faceValues){
    	if (in_array("10", $faceValues) && in_array("11", $faceValues) && in_array("12", $faceValues) && in_array("13", $faceValues) && in_array("14", $faceValues)){ //check for the presence of one of each face card, all face cards + flush = royal flush
    		return true;
    	}
    }

    //Return High Card
    public function getHighCard(array $faceValues){
    	$highCard = $faceValues[4]; //high card will always be the last element, since array is sorted
    	
    	// Return a char in place of value for readability
    	if($highCard == '11'){
    		return 'J';
    	}elseif($highCard == '12'){
    		return 'Q';
    	}elseif($highCard == '13'){
    		return 'K';
    	}elseif($highCard == '14'){
    		return 'A';
    	}else{
    		return $highCard;
    	}
    }

	//Check for pairs, trips, and four-of-a-kind
	public function getSetsOfAKind(array $faceValues) {
	$items = array(
	  'pair' => array(),
	  'three' => 0,
	  'four' => 0,
	);

	// Count the number of each card value present
	$values = array();
	foreach ($faceValues as $faceValue){
		if(!isset($values[$faceValue])){
	  		$values[$faceValue] = 0;
		}
		$values[$faceValue]++;
	}
	// Sort values into pairs, three-of-a-kind, and four-of-a-kind
	// there can only be one item if four-of-a-kind is present
	foreach ($values as $card_value => $kind) {

		//Replace royal values with char for readability
		if($card_value == '11'){
			$card_value = 'J';
		}elseif($card_value == '12'){
			$card_value = 'Q';
		}elseif($card_value == '13'){
			$card_value = 'K';
		}elseif($card_value == '14'){
			$card_value = 'A';
		}

		if ($kind == 4) {
			$items['four'] = $card_value;
			break; //only one
		}
		elseif ($kind == 2) {
			$items['pair'][] = $card_value;
		}
		elseif ($kind == 3) {
			$items['three'] = $card_value;
		}
	}

	return $items;
  }
}
