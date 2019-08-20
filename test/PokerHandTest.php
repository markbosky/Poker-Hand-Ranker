<?php
namespace PokerHand;

use PHPUnit\Framework\TestCase;

class PokerHandTest extends TestCase
{
    /**
     * @test
     */
    public function itCanRankARoyalFlush()
    {
        $hand = new PokerHand('As Ks Qs Js 10s');
        $this->assertEquals('Royal Flush', $hand->getRank());
    }

    /**
     * @test
     */
    public function itCanRankAStraightFlush()
    {
        $hand = new PokerHand('7s 8s 9s 10s Js');
        $this->assertEquals('Straight Flush', $hand->getRank());
    }

    /**
     * @test
     */
    public function itCanRankAPair()
    {
        $hand = new PokerHand('Ah As 10c 7d 6s');
        $this->assertEquals('One Pair', $hand->getRank());
    }

    /**
     * @test
     */
    public function itCanRankTwoPair()
    {
        $hand = new PokerHand('Kh Kc 3s 3h 2d');
        $this->assertEquals('Two Pair', $hand->getRank());
    }

    /**
     * @test
     */
    public function itCanRankAFlush()
    {
        $hand = new PokerHand('Kh Qh 6h 2h 9h');
        $this->assertEquals('Flush', $hand->getRank());
    }

    /**
     * @test
     */
    public function itCanRankTrips()
    {
        $hand = new PokerHand('7h 7s 7d 9h 5c');
        $this->assertEquals('Three of a Kind', $hand->getRank());
    }

    /**
     * @test
     */
    public function itCanRankFours()
    {
        $hand = new PokerHand('7h 7s 7d 7c 2d');
        $this->assertEquals('Four of a Kind', $hand->getRank());
    }

    /**
     * @test
     */
    public function itCanRankStraight()
    {
        $hand = new PokerHand('9h 10s Jd Qc Kd');
        $this->assertEquals('Straight', $hand->getRank());
    }

    /**
     * @test
     */
    public function itCanRankAFullHouse()
    {
        $hand = new PokerHand('7h 7s 7d 10c 10d');
        $this->assertEquals('Full House', $hand->getRank());
    }

    /**
     * @test
     */
    public function itCanRankAHighCard()
    {
        $hand = new PokerHand('5h 4s 8d 10C Ad');
        $this->assertEquals('High Card', $hand->getRank());
    }

    // TODO: More tests go here
}