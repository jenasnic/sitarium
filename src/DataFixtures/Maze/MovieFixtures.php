<?php

namespace App\DataFixtures\Maze;

use App\Entity\Maze\Movie;
use App\Enum\Maze\CastingStatusEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $datas = $this->getDatas();

        foreach ($datas as $data) {
            $manager->persist($data);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    protected function getDatas(): array
    {
        $data = [];

        $data[] = $this->buildData(11, 'La Guerre des étoiles', '1977-05-25 21:57:17', '/yVaQ34IvVDAZAWxScNdeIkaepDq.jpg');
        $data[] = $this->buildData(13, 'Forrest Gump', '1994-07-06 12:59:07', '/4D8CoCW07df1Ryb2qFyY7lxBRGK.jpg');
        $data[] = $this->buildData(18, 'Le Cinquième élément', '1997-05-07 22:09:41', '/93b6qmTZaT8hS4aKU3Aqcj217yt.jpg');
        $data[] = $this->buildData(22, 'Pirates des Caraïbes : La Malédiction du Black Pearl', '2003-07-09 13:03:53', '/6NSeMklnzIshQ2wSZjtZwMRqNf6.jpg');
        $data[] = $this->buildData(63, 'L\'Armée des 12 singes', '1995-12-29 22:07:31', '/mbfW9SlC1VTROWLsN74uumg399L.jpg');
        $data[] = $this->buildData(78, 'Blade Runner', '1982-06-25 22:19:56', '/wuvsU227d8pvHvTRaqkPlsj0OvD.jpg');
        $data[] = $this->buildData(89, 'Indiana Jones et la dernière croisade', '1989-05-24 21:59:25', '/ftdvraBCzt0XiAxIyDwbXu8BgVr.jpg');
        $data[] = $this->buildData(95, 'Armageddon', '1998-07-01 21:34:33', '/rRGgZJMfv4pFHvi4tBmCzEIrvsz.jpg');
        $data[] = $this->buildData(98, 'Gladiator', '2000-05-01 12:59:52', '/3IGZhnjEzZwStzcNOfwICfWikrX.jpg');
        $data[] = $this->buildData(105, 'Retour vers le futur', '1985-07-03 21:58:03', '/gxBJeRQJprw9s7WIRC3C0QkofoL.jpg');
        $data[] = $this->buildData(106, 'Predator', '1987-06-11 13:03:41', '/6E0shu2iXpz4PnM6i0ScfxfHWBm.jpg');
        $data[] = $this->buildData(107, 'Snatch, tu braques ou tu raques', '2000-09-01 13:01:07', '/7v0DxgYK1AzcOyu2vNMINzaySuA.jpg');
        $data[] = $this->buildData(111, 'Scarface', '1983-12-08 22:01:16', '/kECJXf8IsrWgrrqC4cLgf6c8hP8.jpg');
        $data[] = $this->buildData(120, 'Le Seigneur des anneaux : La Communauté de l\'anneau', '2001-12-18 21:55:23', '/1rF04Kk1eunZHk9OHPFwv8hllRF.jpg');
        $data[] = $this->buildData(161, 'Ocean\'s Eleven', '2001-12-07 22:15:29', '/xATFBKVVRKljvIN9XM1JFMFKCr1.jpg');
        $data[] = $this->buildData(218, 'Terminator', '1984-10-26 12:59:44', '/uspqQIN2nyreJKAhhFGpKf7DpTG.jpg');
        $data[] = $this->buildData(238, 'Le Parrain', '1972-03-14 21:54:55', '/j0O2PYvV2INW64jmTc4e5IVqQsz.jpg');
        $data[] = $this->buildData(274, 'Le Silence des agneaux', '1991-02-01 21:58:26', '/qCOKx82fRVzHsSHZIvhDBIOSAY8.jpg');
        $data[] = $this->buildData(278, 'Les Évadés', '1994-09-23 21:55:32', '/5cIUvCJQ2aNPXRCmXiOIuJJxIki.jpg');
        $data[] = $this->buildData(319, 'True Romance', '1993-09-09 22:19:17', '/5gnSBwn7ex9K7u822TiTjsnsOgH.jpg');
        $data[] = $this->buildData(550, 'Fight Club', '1999-10-15 21:55:42', '/zu1BxIKlhTXXIATPINnzg2rwy97.jpg');
        $data[] = $this->buildData(562, 'Piège de cristal', '1988-07-15 13:01:23', '/omoh8uH4U5psmdw3dGTzcO6eql8.jpg');
        $data[] = $this->buildData(564, 'La Momie', '1999-04-16 22:09:16', '/zYjlddckBfIZX9emBpAPFuguFer.jpg');
        $data[] = $this->buildData(603, 'Matrix', '1999-03-30 12:59:15', '/LCcZvB2Ynxg7JOQgviGwZ3l66L.jpg');
        $data[] = $this->buildData(629, 'Usual Suspects', '1995-07-19 12:59:24', '/nG4hOu54DtFQXGKH0qCPIH3cRpW.jpg');
        $data[] = $this->buildData(640, 'Arrête-moi si tu peux', '2002-12-25 21:34:39', '/qMKVkpI2q7bFLL10Zczv8xZH7np.jpg');
        $data[] = $this->buildData(652, 'Troie', '2004-05-13 13:03:21', '/m7N4LFREglUUE7ifrx0Hu5FqtrZ.jpg');
        $data[] = $this->buildData(673, 'Harry Potter et le prisonnier d\'Azkaban', '2004-05-31 22:01:48', '/pDqOMbfVlwVqmTAScSy5fXPfX0s.jpg');
        $data[] = $this->buildData(679, 'Aliens, le retour', '1986-07-18 13:00:18', '/hCFMlY8C3tDsh173C8NhOpf0pEK.jpg');
        $data[] = $this->buildData(680, 'Pulp Fiction', '1994-09-10 12:58:57', '/7p8x4U3o3p1JZMBqNY3zAlobY3m.jpg');
        $data[] = $this->buildData(752, 'V pour vendetta', '2006-03-15 13:01:44', '/A7HFfECsUF2oXSq6BznuhzXWMMO.jpg');
        $data[] = $this->buildData(754, 'Volte/face', '1997-06-27 22:20:34', '/cC7f6NeW1cCmOESD9rpAbGt8E6A.jpg');
        $data[] = $this->buildData(782, 'Bienvenue à Gattaca', '1997-09-07 22:11:22', '/7Gj1i9ajRPB9puaHrxKxjmclRQY.jpg');
        $data[] = $this->buildData(807, 'Seven', '1995-09-22 21:57:29', '/sQGY0TD5od0JUDAyIjo6PE1FVFT.jpg');
        $data[] = $this->buildData(853, 'Stalingrad', '2001-03-14 22:18:34', '/apWjtXCax83q5IqnzITxhCID3bD.jpg');
        $data[] = $this->buildData(855, 'La Chute du faucon noir', '2001-12-28 22:03:11', '/nL87WM4Kwr0Jci7CCEbxZ6Dc8eI.jpg');
        $data[] = $this->buildData(949, 'Heat', '1995-12-15 13:01:35', '/raGQmjQhy3TxfWtZZ9BWhMJQiVl.jpg');
        $data[] = $this->buildData(1091, 'The Thing', '1982-06-25 22:21:50', '/fR9JHElTOA10race5eZNJzzq3E.jpg');
        $data[] = $this->buildData(1103, 'New York 1997', '1981-05-22 22:21:30', '/kVouZkpHuftmbs5Pzb8RRtQ9tHV.jpg');
        $data[] = $this->buildData(1124, 'Le Prestige', '2006-10-19 13:00:03', '/49ZwZyPTWPemvJ4DIaO2H8tgtWT.jpg');
        $data[] = $this->buildData(1366, 'Rocky', '1976-11-21 22:02:26', '/46oSHiifPbhWtfWamvOgAmpN3WG.jpg');
        $data[] = $this->buildData(1368, 'Rambo', '1982-10-22 22:02:39', '/jfx483MCTRINrNYMFjCCHwXGTxp.jpg');
        $data[] = $this->buildData(1422, 'Les infiltrés', '2006-10-05 22:05:00', '/k8LxrSomWzf1dV1YdLadMiDsBZD.jpg');
        $data[] = $this->buildData(1538, 'Collatéral', '2004-08-04 22:23:52', '/5GqI9FqXdlykPrwX7dmYqYEEMRV.jpg');
        $data[] = $this->buildData(1593, 'La Nuit au musée', '2006-10-20 22:11:44', '/aQbH459SMQMu62STrRFtcPgPRCq.jpg');
        $data[] = $this->buildData(1701, 'Les Ailes de l\'enfer', '1997-06-01 22:23:38', '/zlFQDaY6A7owfFjkmu2PU0wefuM.jpg');
        $data[] = $this->buildData(1726, 'Iron Man', '2008-04-30 12:58:29', '/mDTFL6zpd2y0UsqfEY4cG1NgBHI.jpg');
        $data[] = $this->buildData(1830, 'Lord of War', '2005-09-16 22:12:08', '/54CGx18epYWxSHBPGVFq8XfLLJs.jpg');
        $data[] = $this->buildData(1893, 'Star Wars, épisode I - La Menace fantôme', '1999-05-19 21:56:59', '/etnrgeks0Al3wSo44Ji6xgaLBAW.jpg');
        $data[] = $this->buildData(2048, 'I, Robot', '2004-07-15 22:09:04', '/cggVd4Bp4KBTPgD5NQb3oalRX78.jpg');
        $data[] = $this->buildData(2501, 'La Mémoire dans la peau', '2002-06-14 22:06:53', '/nz66IjU7P9mQb8YdFaROunbrgDp.jpg');
        $data[] = $this->buildData(2787, 'Pitch Black', '2000-02-18 22:10:01', '/15GMvhijQbwJvgixBm60z6NmQXL.jpg');
        $data[] = $this->buildData(6114, 'Dracula', '1992-11-13 22:03:24', '/cABnsW2AVX6BqAs3yfKjAMcaGP6.jpg');
        $data[] = $this->buildData(7299, 'Equilibrium', '2002-12-06 22:08:42', '/wClpjK4D0Px3He1dhJyHQtOtQud.jpg');
        $data[] = $this->buildData(8358, 'Seul au monde', '2000-12-22 22:13:29', '/lL168ScwtaZPKBT5pvlxqUtb1N6.jpg');
        $data[] = $this->buildData(8909, 'Wanted : Choisis ton destin', '2008-06-19 22:16:27', '/rUvOujFejJKWjoCfhTESmG9OhsI.jpg');
        $data[] = $this->buildData(9387, 'Conan le Barbare', '1982-04-02 22:08:22', '/kwKwXO4kutz5l1q0z89C9mgTrax.jpg');
        $data[] = $this->buildData(9654, 'Braquage à l\'italienne', '2003-05-30 22:22:32', '/nYPssjhwU4IgxmUWFNKDE5ZvE05.jpg');
        $data[] = $this->buildData(9802, 'Rock', '1996-06-06 22:18:28', '/jkogm1gKE4kWVAsJgzxmmasTAhv.jpg');
        $data[] = $this->buildData(10528, 'Sherlock Holmes', '2009-12-23 22:21:01', '/cvbsO7oyZQbBeI8RZ9aw85JvAO9.jpg');
        $data[] = $this->buildData(11322, 'Public Enemies', '2009-07-01 22:17:57', '/5hIrjTAj2M7N8zaujt4ouORYNxz.jpg');
        $data[] = $this->buildData(11324, 'Shutter Island', '2010-02-18 13:02:01', '/5yVn7RYSypzvSt85P5sDF6LN3VB.jpg');
        $data[] = $this->buildData(13475, 'Star Trek', '2009-05-06 13:03:16', '/bAkwMntqJPtN13TIi4lkM4Jqfpr.jpg');
        $data[] = $this->buildData(13809, 'RockNRolla', '2008-09-04 22:13:17', '/qNr6KOU2WyCTOlTlYf3fAlxSBVy.jpg');
        $data[] = $this->buildData(19995, 'Avatar', '2009-12-10 22:00:58', '/kXlrGioGfFKOvibpsPzzGx16cP2.jpg');
        $data[] = $this->buildData(24428, 'Avengers', '2012-04-25 12:58:18', '/s9UPgyelWtEqjS3HT3TUuHU9BHU.jpg');
        $data[] = $this->buildData(27205, 'Inception', '2010-07-14 12:58:47', '/n9dwu1p5G4qJ4DI5eHJMUbAdOfA.jpg');
        $data[] = $this->buildData(37724, '007 Skyfall', '2012-10-25 13:00:57', '/47UC1BvAKJbpvGXu78bnwlvhFis.jpg');
        $data[] = $this->buildData(45269, 'Le Discours d\'un Roi', '2010-09-06 21:59:34', '/2TEL0a7xJFBORxz5RDeUpwKgh5O.jpg');
        $data[] = $this->buildData(49026, 'The Dark Knight Rises', '2012-07-16 12:58:40', '/1e5V4EBrhuO83JyxQZxOz83xX2Q.jpg');
        $data[] = $this->buildData(49047, 'Gravity', '2013-09-27 22:18:46', '/c5YnXyGEZbLZiSftuHjwSO9kdHK.jpg');
        $data[] = $this->buildData(49517, 'La Taupe', '2011-09-16 21:59:42', '/aopHHvpmfCp6eSkTo5qO9nxdwOP.jpg');
        $data[] = $this->buildData(56292, 'Mission : Impossible - Protocole Fantôme', '2011-12-07 22:12:33', '/bjjuC8WlZ4c7OOxYF7bOHoF9HVr.jpg');
        $data[] = $this->buildData(59440, 'Warrior', '2011-09-09 13:01:52', '/6hiN5qXBfcuhEbHqYem86MDCvlh.jpg');
        $data[] = $this->buildData(61791, 'La Planète des singes : Les Origines', '2011-08-03 22:14:32', '/30DfzUBbrx1XGnMS2bW7nBrCjTd.jpg');
        $data[] = $this->buildData(68718, 'Django Unchained', '2012-12-25 13:00:26', '/iWXGPkf8TwRuXecfDs0j3dDBNLA.jpg');
        $data[] = $this->buildData(70160, 'Hunger Games', '2012-03-12 22:08:50', '/nNpb7F5V7YdGMjp8mPCyVZhiUmv.jpg');
        $data[] = $this->buildData(72190, 'World War Z', '2013-06-20 22:10:49', '/cOWcEXqfrkVBzYTeARow69LCY6C.jpg');
        $data[] = $this->buildData(75612, 'Oblivion', '2013-04-10 22:12:56', '/5lFwBnAlD7OqTqn0S0WtbGMKS0l.jpg');
        $data[] = $this->buildData(75656, 'Insaisissables', '2013-05-29 22:22:54', '/cjWV1wGnZy12ZXEMTYUzXzeLpUz.jpg');
        $data[] = $this->buildData(76341, 'Mad Max : Fury Road', '2015-05-13 13:02:11', '/6jkviwPHZPHGHRu6QhECU2mbO05.jpg');
        $data[] = $this->buildData(116745, 'La Vie rêvée de Walter Mitty', '2013-12-18 22:09:25', '/aGjWSKocwOuADJqIbRWD0Evacc3.jpg');
        $data[] = $this->buildData(127585, 'X-Men : Days of Future Past', '2014-05-15 22:06:22', '/mcJ2df14G595oqIYiSmgefvOo6d.jpg');
        $data[] = $this->buildData(135397, 'Jurassic World', '2015-06-09 13:02:22', '/aTxRptK9fG3a0Tmcym3h4LXFOlm.jpg');
        $data[] = $this->buildData(156022, 'Equalizer', '2014-09-24 22:10:34', '/r1Hs2oDOyWNkd9mtlftPy25kwbW.jpg');
        $data[] = $this->buildData(157336, 'Interstellar', '2014-11-05 12:59:32', '/qAr3kvQeKu1UMOOrt50kUqEkdlr.jpg');
        $data[] = $this->buildData(168672, 'American Bluff', '2013-12-12 22:15:54', '/hQTZMFWCzkTtE1oARIZD8TjElRo.jpg');
        $data[] = $this->buildData(207703, 'Kingsman : Services Secrets', '2015-01-29 22:01:08', '/enDe6WbsUu5wqRmqLdgByyjgIwL.jpg');
        $data[] = $this->buildData(245891, 'John Wick', '2014-10-22 22:03:43', '/tyPtD94Gx1JFkWgGO3nrmv1GmpL.jpg');
        $data[] = $this->buildData(263115, 'Logan', '2017-02-28 22:05:38', '/5HB2SsrYNARm4Kom7Amwyb93O4M.jpg');
        $data[] = $this->buildData(274870, 'Passengers', '2016-12-21 22:17:39', '/oqV0RDmKWXJm7xv789FSb37rmYE.jpg');
        $data[] = $this->buildData(281957, 'The Revenant', '2015-12-25 22:05:29', '/AsDto68FNWNXD15Ov0yMXASJiSB.jpg');
        $data[] = $this->buildData(283995, 'Les Gardiens de la Galaxie Vol. 2', '2017-04-19 13:02:43', '/8Xo5iajzeK1PYeidHK2PPsgli2x.jpg');
        $data[] = $this->buildData(284052, 'Doctor Strange', '2016-10-25 22:08:34', '/gwi5kL7HEWAOTffiA14e4SbOGra.jpg');
        $data[] = $this->buildData(286217, 'Seul sur Mars', '2015-09-30 22:13:37', '/dCeDXsGWmVG5RYU6oeMYve7MAo5.jpg');
        $data[] = $this->buildData(293660, 'Deadpool', '2016-02-09 22:25:57', '/nsHOWVQcinAWRtGeIbfZJCTDoNX.jpg');
        $data[] = $this->buildData(302946, 'Mr Wolff', '2016-10-14 22:12:43', '/1fJlnVEyVRcOlbraJCUsiRQ9Js1.jpg');
        $data[] = $this->buildData(337339, 'Fast & Furious 8', '2017-04-12 22:13:52', '/43XSMNTEba4HeaUCEMOX7DYe7NM.jpg');
        $data[] = $this->buildData(339846, 'BAYWATCH: Alerte à Malibu', '2017-05-12 22:11:06', '/m1ckF58A00hoiMNPInM7ZKcAc5S.jpg');
        $data[] = $this->buildData(363676, 'Sully', '2016-09-07 14:06:30', '/sfIY3pBickp9YoMFKQGwVc4FXwV.jpg');

        return $data;
    }

    /**
     * @param int $tmdbId
     * @param string $title
     * @param string $releaseDate
     * @param string $pictureUrl
     *
     * @return Movie
     */
    protected function buildData(
        int $tmdbId,
        string $title,
        string $releaseDate,
        string $pictureUrl
    ): Movie {
        $data = new Movie();
        $data->setTmdbId($tmdbId);
        $data->setTitle($title);
        $data->setReleaseDate(\DateTime::createFromFormat('Y-m-d H:i:s', $releaseDate));
        $data->setPictureUrl($pictureUrl);
        $data->setStatus(CastingStatusEnum::UNINITIALIZED);

        return $data;
    }
}
