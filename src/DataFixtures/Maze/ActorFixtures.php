<?php

namespace App\DataFixtures\Maze;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Maze\Actor;
use App\Enum\Maze\FilmographyStatus;

class ActorFixtures extends Fixture
{
    /**
     * {@inheritDoc}
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

        $data[] = $this->buildData(3, 'Harrison Ford', '1942-07-13 21:23:38', '/7CcoVFTogQgex2kJkXKMe8qHZrC.jpg');
        $data[] = $this->buildData(31, 'Tom Hanks', '1956-07-09 00:36:08', '/pQFoyx7rp09CJTAb932F2g8Nlho.jpg');
        $data[] = $this->buildData(62, 'Bruce Willis', '1955-03-19 00:37:10', '/2B7RySy2WMVJKKEFN2XA3IFb8w0.jpg');
        $data[] = $this->buildData(64, 'Gary Oldman', '1958-03-21 00:36:37', '/tofLS5A6lBXNjeROGvgpfe2JwaT.jpg');
        $data[] = $this->buildData(85, 'Johnny Depp', '1963-06-09 00:35:36', '/ea4fTp9T8Zy2KWxyFsqDIBgHZmb.jpg');
        $data[] = $this->buildData(112, 'Cate Blanchett', '1969-05-14 00:39:52', '/h8LD9WBgnfJc47bRthTOVTchvJ3.jpg');
        $data[] = $this->buildData(114, 'Orlando Bloom', '1977-01-13 21:47:17', '/3G6baESq7EV676WttQYdUDiz4X2.jpg');
        $data[] = $this->buildData(116, 'Keira Knightley', '1985-03-26 00:39:17', '/rv6quYbTgFTmBAoePwy5xuurW3g.jpg');
        $data[] = $this->buildData(118, 'Geoffrey Rush', '1951-07-06 21:43:33', '/5h91WHSK80YtqTk1bMiar2IZzO2.jpg');
        $data[] = $this->buildData(134, 'Jamie Foxx', '1967-12-13 21:22:38', '/cDRGXCNrwf1p9jouw9GzhU2vWP7.jpg');
        $data[] = $this->buildData(139, 'Uma Thurman', '1970-04-29 21:34:52', '/6SuOc2R7kXjq3Em24KTNDW9qblJ.jpg');
        $data[] = $this->buildData(192, 'Morgan Freeman', '1937-06-01 00:36:44', '/oGJQhOpT8S1M56tvSsbEBePV5O1.jpg');
        $data[] = $this->buildData(204, 'Kate Winslet', '1975-10-05 21:28:41', '/4dnurP9Szr9y6S3nTkd3pHUQg5b.jpg');
        $data[] = $this->buildData(228, 'Ed Harris', '1950-11-28 21:47:47', '/atzm7ZGRFSWJHQT6qKmzjmNQ9GA.jpg');
        $data[] = $this->buildData(287, 'Brad Pitt', '1963-12-18 00:35:53', '/ejYIW1enUcGJ9GS3Bs34mtONwWS.jpg');
        $data[] = $this->buildData(368, 'Reese Witherspoon', '1976-03-22 21:47:53', '/a3o8T1P6yy4KWL7wZG6HuDeuh5n.jpg');
        $data[] = $this->buildData(380, 'Robert De Niro', '1943-08-17 00:35:59', '/lvTSwUcvJRLAJ2FB5qFaukel516.jpg');
        $data[] = $this->buildData(500, 'Tom Cruise', '1962-07-03 00:35:44', '/3oWEuo0e8Nx8JvkqYCDec2iMY6K.jpg');
        $data[] = $this->buildData(514, 'Jack Nicholson', '1937-04-22 21:20:37', '/hINAkm21g80UbaAxA6rHhOaT5Jk.jpg');
        $data[] = $this->buildData(524, 'Natalie Portman', '1981-06-09 00:39:08', '/jJcRWku3e9OHrmRqytn6WcBjhvh.jpg');
        $data[] = $this->buildData(530, 'Carrie-Anne Moss', '1967-08-21 21:45:32', '/6gk8GmlfjW8ONS19KMeISp8Cqxf.jpg');
        $data[] = $this->buildData(569, 'Ethan Hawke', '1970-11-06 21:45:55', '/kcby6VYk6Gb0036nUyh8chY5ZAJ.jpg');
        $data[] = $this->buildData(880, 'Ben Affleck', '1972-08-15 21:22:12', '/7Zy12dUasr43oF8hyU5iBz5iOpO.jpg');
        $data[] = $this->buildData(884, 'Steve Buscemi', '1957-12-13 21:26:39', '/e19GfOWzMNN1hi7B9Ci62hMvtXs.jpg');
        $data[] = $this->buildData(976, 'Jason Statham', '1967-07-26 21:19:35', '/PhWiWgasncGWD9LdbsGcmxkV4r.jpg');
        $data[] = $this->buildData(1100, 'Arnold Schwarzenegger', '1947-07-30 00:37:42', '/sOkCXc9xuSr6v7mdAq9LwEBje68.jpg');
        $data[] = $this->buildData(1158, 'Al Pacino', '1940-04-25 21:19:21', '/ks7Ba8x9fJUlP9decBr6Dh5mThX.jpg');
        $data[] = $this->buildData(1204, 'Julia Roberts', '1967-10-28 21:28:23', '/h13yvG0tRNMTAwciQXxYmQWdYW8.jpg');
        $data[] = $this->buildData(1231, 'Julianne Moore', '1960-12-03 21:28:29', '/v2FcWGiiuvl6P7NV0966jNL09uh.jpg');
        $data[] = $this->buildData(1245, 'Scarlett Johansson', '1984-11-22 00:38:49', '/eYFHUWxTCNg6lPypJCaUQXhoUop.jpg');
        $data[] = $this->buildData(1333, 'Andy Serkis', '1964-04-20 21:45:23', '/nQRsxFveJaUIlZ4GYWDe9uJ6u2f.jpg');
        $data[] = $this->buildData(1461, 'George Clooney', '1961-05-06 21:18:42', '/esyiULfB7kSrhgzBkLamjsTTKEg.jpg');
        $data[] = $this->buildData(1813, 'Anne Hathaway', '1982-11-12 00:40:10', '/4Nh1zDDrV8ZrhmKCdDfHvZGwOSq.jpg');
        $data[] = $this->buildData(1892, 'Matt Damon', '1970-10-08 00:36:30', '/elSlNgV8xVifsbHpFsqrPGxJToZ.jpg');
        $data[] = $this->buildData(1920, 'Winona Ryder', '1971-10-29 21:27:00', '/gUyEOZpZlGBUkUxCxyoLEc9WejR.jpg');
        $data[] = $this->buildData(1979, 'Kevin Spacey', '1959-07-26 21:17:22', '/x7wF050iuCASefLLG75s2uDPFUu.jpg');
        $data[] = $this->buildData(2231, 'Samuel L. Jackson', '1948-12-21 00:37:02', '/jyFUkDAP0XXHQDsDhufZWZG25y.jpg');
        $data[] = $this->buildData(2282, 'Ben Kingsley', '1943-12-31 21:39:26', '/2Eu3j31JDJek70ZXLY6xfeUaJoR.jpg');
        $data[] = $this->buildData(2461, 'Mel Gibson', '1956-01-03 21:30:13', '/6VGgL0bBvPIJ9vDOyyGf5nK2zL4.jpg');
        $data[] = $this->buildData(2524, 'Tom Hardy', '1977-09-15 00:37:17', '/mHSmt9qu2JzEPqnVWCGViv9Stnn.jpg');
        $data[] = $this->buildData(2713, 'Linda Hamilton', '1956-09-26 21:36:42', '/fcRpgjonpH3WmPs0V63g7iP7Dbm.jpg');
        $data[] = $this->buildData(2888, 'Will Smith', '1968-09-25 21:20:45', '/5V4OKHQpIEffF9rqgzGYK4TNaGg.jpg');
        $data[] = $this->buildData(2963, 'Nicolas Cage', '1964-01-07 00:38:11', '/ti2h1OS1n1VwoJHWFaJD8dMZuEE.jpg');
        $data[] = $this->buildData(3061, 'Ewan McGregor', '1971-03-31 21:33:46', '/lVjs6E3vriUXhHrAx0mSzyOVts2.jpg');
        $data[] = $this->buildData(3223, 'Robert Downey Jr.', '1965-04-04 00:36:15', '/1YjdSym1jTG7xjHSI0yGGWEsw5i.jpg');
        $data[] = $this->buildData(3293, 'Rachel Weisz', '1970-03-07 00:39:24', '/wV2QxhLUHVFAkdvLxzO26o5ncmX.jpg');
        $data[] = $this->buildData(3489, 'Naomi Watts', '1968-09-28 00:40:02', '/8W02WOJI1pEGh2iqQsgITR5tV0P.jpg');
        $data[] = $this->buildData(3894, 'Christian Bale', '1974-01-30 00:36:23', '/pPXnqoGD91znz4FwQ6aKuxi6Pcy.jpg');
        $data[] = $this->buildData(3895, 'Michael Caine', '1933-03-14 21:35:04', '/vvj0JMSFpOajXCE46Hy4dyqSP2U.jpg');
        $data[] = $this->buildData(3896, 'Liam Neeson', '1952-06-07 21:20:03', '/9mdAohLsDu36WaXV2N3SQ388bvz.jpg');
        $data[] = $this->buildData(3967, 'Kate Beckinsale', '1973-07-26 21:39:19', '/pTRtcZn9gWQZRiet36qWKh94urn.jpg');
        $data[] = $this->buildData(4173, 'Anthony Hopkins', '1937-12-31 21:18:07', '/jdoBTIru71FbPuHGEgox5RVmIO0.jpg');
        $data[] = $this->buildData(4587, 'Halle Berry', '1966-08-14 21:51:10', '/wjsOp5kvfbG9Vu6QDY8rGCuHs6m.jpg');
        $data[] = $this->buildData(5081, 'Emily Blunt', '1983-02-23 21:36:13', '/w5bjRgGy3vNkJqY97kbvTp7ldxb.jpg');
        $data[] = $this->buildData(5292, 'Denzel Washington', '1954-12-28 21:21:52', '/khMf8LLTtppUwuZqqnigD2nAy26.jpg');
        $data[] = $this->buildData(5293, 'Willem Dafoe', '1955-07-22 21:49:42', '/xM5lhOR5tWWdIlFpBDeZJx9opIP.jpg');
        $data[] = $this->buildData(5309, 'Judi Dench', '1934-12-09 21:26:23', '/2is9RvJ3BQAku2EtCmyk5EZoxzT.jpg');
        $data[] = $this->buildData(5472, 'Colin Firth', '1960-09-10 21:42:42', '/lKUq407IhFF6CQoJbUgbEyfS9JA.jpg');
        $data[] = $this->buildData(5576, 'Val Kilmer', '1959-12-31 21:19:28', '/AlhPeiH8R4reMNGNQ9ag1FPbuW9.jpg');
        $data[] = $this->buildData(6193, 'Leonardo DiCaprio', '1974-11-11 00:35:27', '/jToSMocaCaS5YnuOJVqQ7S7pr4Q.jpg');
        $data[] = $this->buildData(6384, 'Keanu Reeves', '1964-09-02 21:18:35', '/bOlYWhVuOiU6azC4Bw6zlXZ5QTC.jpg');
        $data[] = $this->buildData(6856, 'Kurt Russell', '1951-03-17 21:22:18', '/rlnFuNkisPpuypARI7QaGCmOY6V.jpg');
        $data[] = $this->buildData(6885, 'Charlize Theron', '1975-08-08 00:39:34', '/k5Xt2mNlraX7yHYaPy9gvayCaKV.jpg');
        $data[] = $this->buildData(6968, 'Hugh Jackman', '1968-10-12 21:16:55', '/oOqun0BhA1rLXOi7Q1WdvXAkmW.jpg');
        $data[] = $this->buildData(7399, 'Ben Stiller', '1965-11-30 21:44:49', '/o9M2PyoF7QDSMq1OwW0D892fIkV.jpg');
        $data[] = $this->buildData(8293, 'Marion Cotillard', '1975-09-30 21:31:10', '/waTZo741VcFfRUvzQYhKVrVr6MT.jpg');
        $data[] = $this->buildData(8691, 'Zoe Saldana', '1978-06-19 21:38:43', '/ofNrWiA2KDdqiNxFTLp51HcXUlp.jpg');
        $data[] = $this->buildData(8784, 'Daniel Craig', '1968-03-02 21:30:43', '/mr6cdu6lLRscfFUv8onVWZqaRdZ.jpg');
        $data[] = $this->buildData(8891, 'John Travolta', '1954-02-18 21:46:03', '/ns8uZHEHzV18ifqA9secv8c2Ard.jpg');
        $data[] = $this->buildData(9273, 'Amy Adams', '1974-08-20 21:28:55', '/jkMGu8ngOIb9Z1M4zzDmQlPOUHz.jpg');
        $data[] = $this->buildData(9642, 'Jude Law', '1972-12-29 21:45:46', '/yzVboK3mSpWRr39EXBlYTo4ndcn.jpg');
        $data[] = $this->buildData(10297, 'Matthew McConaughey', '1969-11-04 21:40:29', '/jdRmHrG0TWXGhs4tO6TJNSoL25T.jpg');
        $data[] = $this->buildData(10859, 'Ryan Reynolds', '1976-10-23 21:29:42', '/h1co81QaT2nJA41Sb7eZwmWl1L2.jpg');
        $data[] = $this->buildData(10912, 'Eva Green', '1980-07-06 00:38:32', '/rwmLtchv0uwUYWSNbixY3GGELJ2.jpg');
        $data[] = $this->buildData(10980, 'Daniel Radcliffe', '1989-07-23 21:33:22', '/kMSMa5tR43TLMR14ahU1neFVytz.jpg');
        $data[] = $this->buildData(10990, 'Emma Watson', '1990-04-15 00:38:56', '/pMjCFPe3oLBaVXw7qfFzrwA0WXD.jpg');
        $data[] = $this->buildData(12052, 'Gwyneth Paltrow', '1972-09-27 00:39:44', '/uSidH77gLyon3Zpg6y1GxpKJMdZ.jpg');
        $data[] = $this->buildData(12835, 'Vin Diesel', '1967-07-18 00:38:03', '/7rwSXluNWZAluYMOEWBxkPmckES.jpg');
        $data[] = $this->buildData(13240, 'Mark Wahlberg', '1971-06-05 21:17:59', '/tdPF78kdzxPcCwxKjPykq6u3y5Z.jpg');
        $data[] = $this->buildData(16483, 'Sylvester Stallone', '1946-07-06 00:37:31', '/gnmwOa46C2TP35N7ARSzboTdx2u.jpg');
        $data[] = $this->buildData(16828, 'Chris Evans', '1981-06-13 21:21:03', '/8CgFKCZJVwZxa1F88n8drEux0vT.jpg');
        $data[] = $this->buildData(17276, 'Gerard Butler', '1969-11-13 21:21:09', '/i54XoxYieuff2w6MwyfwVUBvmR0.jpg');
        $data[] = $this->buildData(17288, 'Michael Fassbender', '1977-04-02 21:24:46', '/hvtyvcLd3hqRiYECH0bP9IJXCX2.jpg');
        $data[] = $this->buildData(17605, 'Idris Elba', '1972-09-06 21:22:48', '/d9NkfCwczP0TjgrjpF94jF67SK8.jpg');
        $data[] = $this->buildData(17647, 'Michelle Rodriguez', '1978-07-12 21:38:35', '/v37VK0MNuRuJOCKPKJcZAJXRA5r.jpg');
        $data[] = $this->buildData(18918, 'Dwayne Johnson', '1972-05-02 00:37:51', '/kuqFzlYMc2IrsOyPznMd1FroeGq.jpg');
        $data[] = $this->buildData(27319, 'Christoph Waltz', '1956-10-04 21:45:05', '/bPtNS4p3CEDt3Uo9khMCLyQUa0W.jpg');
        $data[] = $this->buildData(30614, 'Ryan Gosling', '1980-11-12 21:35:59', '/5rOcicCrTCWye0O2S3dnbnWaCr1.jpg');
        $data[] = $this->buildData(33192, 'Joel Edgerton', '1974-06-23 21:44:07', '/lkOkaMKSRRGLMgkLaCzR9sYgTgx.jpg');
        $data[] = $this->buildData(51329, 'Bradley Cooper', '1975-01-05 21:40:36', '/z5LUl9bljJnah3S5rtN7rScrmI8.jpg');
        $data[] = $this->buildData(62064, 'Chris Pine', '1980-08-26 21:17:01', '/vSe6sIsdtcoqBhuWRXynahFg8Vf.jpg');
        $data[] = $this->buildData(71580, 'Benedict Cumberbatch', '1976-07-19 21:18:22', '/wz3MRiMmoz6b5X3oSzMRC9nLxY1.jpg');
        $data[] = $this->buildData(72129, 'Jennifer Lawrence', '1990-08-15 00:38:24', '/q0tf3XEo7wa8XglIznTC7WzZ9W3.jpg');
        $data[] = $this->buildData(73457, 'Chris Pratt', '1979-06-21 00:36:53', '/n4DD1AYU7WEMNPLga1TxqnHivn1.jpg');
        $data[] = $this->buildData(74568, 'Chris Hemsworth', '1983-08-11 21:17:11', '/lrhth7yK9p3vy6p7AabDUM1THKl.jpg');
        $data[] = $this->buildData(87722, 'Noomi Rapace', '1979-12-28 21:42:29', '/xllWXMaKlPSQ9LUkG9niugX8vOx.jpg');
        $data[] = $this->buildData(91606, 'Tom Hiddleston', '1981-02-09 21:40:14', '/qB1lHPFBPIzw6I7EvsciZ5wyUNS.jpg');
        $data[] = $this->buildData(121529, 'Léa Seydoux', '1985-07-01 21:32:09', '/xptyFXfBENBMQkgPEYCxlMkSLSs.jpg');
        $data[] = $this->buildData(1223786, 'Emilia Clarke', '1986-10-23 21:37:16', '/j7d083zIMhwnKro3tQqDz2Fq1UD.jpg');
        $data[] = $this->buildData(1303037, 'Taron Egerton', '1989-11-10 21:43:24', '/uCaPhyKAQIfEzAWWCYIrO2004CB.jpg');

        return $data;
    }

    protected function buildData(
        int $tmdbId,
        string $fullname,
        string $birthdate,
        string $pictureUrl
    ): Actor {
        $data = new Actor();
        $data->setTmdbId($tmdbId);
        $data->setFullname($fullname);
        $data->setBirthdate(\DateTime::createFromFormat('Y-m-d H:i:s', $birthdate));
        $data->setPictureUrl($pictureUrl);
        $data->setStatus(FilmographyStatus::UNINITIALIZED);

        return $data;
    }
}
