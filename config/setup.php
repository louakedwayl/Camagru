<?php
// config/setup.php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/../models/UserModel.php';

const CAPTIONS = [
    1 => "A frozen stage for raw connection. Blades strike, time slows, and balance becomes instinct. Two bodies in sync, carving stories into the ice. There's something primal about this dance on frozen water, where trust isn't spoken but felt through every movement. The cold air bites, but the warmth of partnership cuts through it. In these moments, you realize it's not about perfection—it's about presence, about finding rhythm in the chaos, about two souls learning to move as one. The ice remembers every mark we leave, a temporary testimony to something timeless.",
    
    2 => "Beneath the surface, it's all control and endurance. Precision in every stroke, power in every turn. Competition strips it down to what truly matters. The water becomes both opponent and ally, pushing back against every movement while carrying you forward. Hours of training condense into seconds of pure execution, where muscle memory takes over and the mind goes quiet. This is where you discover what you're truly made of—not in the comfort of preparation, but in the demanding crucible of performance. Every breath is earned, every meter fought for, and in that struggle, you find a clarity that exists nowhere else in life.",
    
    3 => "The art of the wood-fired pizza perfected. There is no substitute for the smoky aroma of a traditional brick oven and dough that has been handled with care. From the roaring fire to the fresh ingredients on the table, this is dinner exactly as it was meant to be. Watching the flames dance and lick at the oven's interior, you understand why some traditions refuse to die. The heat is intense, transforming simple ingredients into something transcendent. Each pizza emerges with its edges perfectly charred, the cheese bubbling with golden pools of oil, and the crust achieving that ideal balance of crispy and chewy. This isn't fast food—it's a ritual, a celebration of patience and craft that turns a meal into a memory.",
    
    4 => "Witnessing centuries of tradition in every swing. Pelota is more than just a sport: it's a masterclass in precision and power. Seeing the players move with such grace and speed against the massive concrete fronton is a reminder of how deep these roots go. Truly an unforgettable experience. The ball cracks against the wall with a sound that echoes through generations, a rhythm that's been beating in Basque hearts for hundreds of years. The players' movements are poetry written in sweat and determination, their bodies attuned to a game that demands everything and forgives nothing. Standing here, you're not just watching a match—you're witnessing living history, feeling the pulse of a culture that refuses to be forgotten, that passes its passion from father to son, from teacher to student, keeping the flame alive.",
    
    5 => "Golden light and the scent of ripe apples filling the air. This is the heart of the harvest season, where every tree tells a story of patience and growth. A quiet moment to appreciate the vibrant colors and the fresh, open landscape. The orchard stretches out like nature's cathedral, rows upon rows of trees heavy with fruit that's been months in the making. You can taste autumn in the breeze, feel the satisfaction of a season's work coming to fruition. There's a particular kind of peace that comes from being surrounded by abundance, from picking fruit warmed by the sun and knowing exactly where your food comes from. In our hyper-connected world, these simple pleasures ground us, remind us of cycles older than cities, more reliable than technology.",
    
    6 => "Surrounded by motion and sound, they move at their own pace. A mother and her son, grounded in something deeper than the spectacle. The world rushes by in a blur of noise and distraction, but they exist in their own bubble of connection. Her hand guides him gently, protectively, while his trust in her is absolute and unquestioning. These are the moments that matter—not the grand gestures or the Instagram-worthy backdrops, but the quiet assurance of being present for someone who needs you. In a culture that celebrates independence, there's something profoundly beautiful about interdependence, about choosing to slow down and walk at someone else's pace. This is love in its purest form: patient, steady, unconditional.",
    
    7 => "Salt in the air, focus in the eyes. One surfer, endless water, and the quiet power of the Australian coast. The ocean doesn't negotiate, doesn't compromise, and that's exactly what makes it the perfect teacher. Out here, stripped of all pretense and distraction, you face yourself as much as you face the waves. Each swell is a new opportunity, each wipeout a lesson in humility. The sun beats down mercilessly, the water chills you to the bone, and yet there's nowhere else you'd rather be. This is freedom measured in moments of perfect balance, in the brief triumph of riding a wave before it inevitably reclaims you. The Australian coast has shaped generations of surfers, and in return, surfing has shaped their souls.",
    
    8 => "Behind every plate lies years of practice. A Japanese chef, a humble kitchen, and the quiet intensity that turns food into experience. There's a particular kind of dedication that Japanese culinary tradition demands—not the loud, aggressive perfectionism of Western kitchens, but a calm, meditative pursuit of mastery that can take decades to achieve. Every slice of the knife is deliberate, every ingredient treated with reverence. The chef doesn't seek fame or recognition; the work itself is the reward. In this small kitchen, magic happens not through spectacle but through discipline, not through innovation for its own sake but through the patient refinement of techniques passed down through generations. To eat here is to taste history, philosophy, and soul on a single plate.",
    
    9 => "A quiet chapter shared in the heart of the hills. There is a timeless quality to an afternoon spent with a loyal companion and a good book, especially with a backdrop as storied as this. Some friendships are written in the landscape, built on silent understanding and the simple joy of being present in the moment. The dog doesn't need words to communicate contentment, and neither do you. The pages turn slowly, the sun traces its arc across the sky, and time loses its tyranny. These hills have witnessed countless stories, but right now, this small one is enough. In our age of constant connectivity and chronic distraction, moments like these are acts of rebellion—choosing stillness over stimulation, depth over distraction, presence over productivity.",
    
    10 => "A masterclass in defensive timing and offensive drive. In a high-stakes match like this, the margin for error is nonexistent. Capturing that split second where balance and power collide, proving once again why this is the world's most captivating game. The tension is palpable, every touch of the ball potentially decisive, every movement scrutinized by thousands of eyes. Years of training compress into milliseconds of decision-making, where instinct and intelligence merge into something approaching art. This is football at its finest—not just athletic prowess but tactical genius, not just individual brilliance but collective intelligence. The beautiful game earns its name in moments like these, when human potential is displayed in its most dynamic, dramatic form.",
    
    11 => "Behind the scenes where the real magic happens. A dedicated team working in perfect harmony, turning raw ingredients into a series of coordinated masterpieces. The energy of a professional kitchen is unlike anything else, driven by passion, timing, and a relentless pursuit of perfection. The choreography is precise—each member knows their role, their timing, their responsibility to the collective effort. Orders fire, plates go out, and the dance continues without missing a beat. This is pressure that would break most people, yet these chefs thrive in it, finding flow in the chaos. The heat is oppressive, the pace unrelenting, but there's a camaraderie forged in these conditions that can't be replicated anywhere else. They don't just cook together—they create together, bleed together, triumph together.",
    
    12 => "In the arena, control meets chaos. Players rise, defenders react, and the game unfolds like a masterclass in focus and athleticism. Every jump is calculated yet explosive, every defensive read a combination of study and instinct. The crowd roars but the players exist in a bubble of concentration, their awareness narrowed to the court, the ball, the opportunity. This is basketball poetry—fluid, improvisational, yet structured within the framework of strategy and system. Bodies collide, sweat flies, and in the midst of it all, moments of pure grace emerge. These athletes aren't just physically gifted; they're students of the game, perpetually learning, adapting, evolving. This is sport as art, competition as expression.",
    
    13 => "A true feast for the senses where the options are as endless as the appetite. There is a unique kind of communal energy in a crowded buffet line, where everyone is on their own personal quest for the perfect plate. From savory classics to new discoveries, it is a celebration of variety and plenty. The steam rises from dozens of dishes, each one calling out to be tried, to be savored. You become an explorer, a curator of your own culinary adventure, balancing familiar favorites with daring experiments. There's democracy in a buffet—no judgment, no pretension, just pure possibility. Sure, purists might scoff, but they're missing the point. This isn't about Michelin stars; it's about abundance, about choice, about the simple joy of trying a little bit of everything without apology or restraint.",
    
    14 => "Chasing high scores and neon dreams in a corner of the city that time forgot. There is something timeless about the hum of a pinball machine and the dim glow of a dive bar. It is the perfect escape from the digital world, one silver ball at a time. The machine demands your full attention—no multitasking, no notifications, just you versus gravity and physics and luck. Your hands work the flippers with increasing desperation as the ball careens unpredictably around the playfield. Each game is a story with a beginning, middle, and end, compressed into a few intense minutes. The beer is cold, the company is easy, and for once, your phone stays in your pocket. This is analog entertainment in a digital age, and it's exactly what you didn't know you needed.",
    
    15 => "Layers of green, carved with care. In the rizières, time slows, and every ripple reflects a cycle older than memory. These terraced rice fields represent generations of knowledge, of backbreaking work, of harmony with the land. The water mirrors the sky, creating an illusion of infinite space, of earth meeting heaven. Farmers move through the paddies with practiced efficiency, their movements economic and purposeful. This landscape wasn't given—it was created, shaped by human hands working in partnership with nature rather than in opposition to it. Standing here, you understand that sustainability isn't a modern buzzword; it's an ancient practice, a way of life that has fed communities for centuries without exhausting the soil or poisoning the water. There's wisdom in these fields that no university can teach."
];

const XAVIER_CAPTIONS = [
    1 => "Building the future, one line of code at a time. Technology isn't just about innovation—it's about democratization, about giving people the tools to create their own destinies. I've always believed that the next great breakthrough won't come from someone with the right pedigree or the perfect resume. It'll come from someone with hunger, with vision, with the audacity to imagine a world that doesn't exist yet and the determination to code it into reality. That's why I invest in education, in access, in tearing down the barriers that keep brilliant minds trapped in circumstances they didn't choose. The future belongs to those brave enough to build it.",
    
    2 => "Innovation starts where comfort zones end. Too many people play it safe, optimize for incremental gains, avoid risk like it's a disease. But real progress—the kind that changes industries and transforms lives—requires stepping into the unknown, embracing uncertainty, accepting that failure is part of the process. I've failed more times than most people have tried, and each failure taught me something that success never could. The entrepreneurs I respect most aren't the ones with the smoothest trajectories; they're the ones who've been knocked down, who've lost everything, who've had to rebuild from scratch. That's where character is forged. That's where true innovation is born.",
    
    3 => "The best investment is in people who dare to learn. Money comes and goes, markets rise and fall, but education—real education, the kind that teaches you how to think rather than what to think—that's forever. I've poured resources into 42 because I believe that talent is everywhere but opportunity is not. I've seen kids from housing projects outcode Ivy League graduates. I've watched people with no formal training build products that disrupt entire industries. The traditional education system is broken—it sorts people into winners and losers based on arbitrary criteria, then charges a fortune for the privilege. We're building something different: a meritocracy based purely on ability and effort. No tuition, no teachers, no bullshit. Just you, a computer, and the challenge of becoming who you're capable of being.",
    
    4 => "Freedom to fail is freedom to succeed. We've created a culture where failure is stigmatized, where one mistake can derail a career, where playing it safe is the only rational strategy. That's not just sad—it's economically destructive. The biggest innovations in human history came from people who were willing to risk everything on an idea that most people thought was crazy. If we want a dynamic, creative, prosperous society, we need to celebrate the people who take risks, who try audacious things, who fail spectacularly and get back up to try again. That's the spirit I try to foster in every company I'm involved with, every investment I make. Fail fast, learn faster, and never let fear of judgment stop you from pursuing something great."
];

const ECOLE42_CAPTIONS = [
    1 => "No teachers, no classes, just pure peer-to-peer learning. This isn't a bug—it's the entire feature. We've stripped away everything that doesn't work about traditional education and built something radically different from the ground up. Students teach each other, challenge each other, push each other to be better. There's no hiding in the back of a lecture hall, no passive absorption of information. You're in the arena from day one, coding, debugging, collaborating, failing, learning. The result? Graduates who don't just know how to program—they know how to learn, how to adapt, how to solve problems they've never seen before. In a world where technology evolves faster than any curriculum can keep up, that's the only skill that truly matters.",
    
    2 => "Where passion meets code, magic happens. You can't fake your way through 42. You either love this, or you burn out in the first month. The piscine—our intensive entrance bootcamp—isn't designed to teach you everything; it's designed to reveal who you really are. Are you someone who gives up when things get hard, or someone who digs deeper? Do you hoard knowledge, or share it freely? Can you work alone, and can you work in a team? The code is just the medium through which these deeper truths emerge. And for those who make it through, who discover that they have reserves of determination they didn't know existed, who find their tribe among fellow obsessives—magic happens.",
    
    3 => "42: because the answer was never in a textbook. We named ourselves after Douglas Adams' famous number not as a joke, but as a philosophy. The ultimate answer to life, the universe, and everything can't be handed to you—you have to discover it yourself through questioning, experimentation, and lived experience. Textbooks become outdated the moment they're published. Lectures put students to sleep. Grades reduce complex understanding to a single letter. We threw all of that out and asked a simple question: what if we created an environment where the only limit on learning was the student's own curiosity and drive? The answer, it turns out, is pretty incredible.",
    
    4 => "Late nights, strong coffee, and commits that matter. There's a particular energy that takes over the campus after midnight. The casual learners have gone home, and what remains are the truly committed—the ones who can't stop thinking about the problem they're working on, who've been wrestling with a bug for hours and refuse to give up, who are so close to a breakthrough they can taste it. These are the moments that define a 42 education: not the structured hours, not the official curriculum, but these voluntary, obsessive, beautiful struggles. The coffee is terrible, the furniture is uncomfortable, but none of that matters because you're exactly where you want to be, doing exactly what you love.",
    
    5 => "Learning to learn is the ultimate skill. In ten years, half the programming languages used today will be obsolete, replaced by tools and frameworks we can't even imagine yet. So what's the point of teaching specific technologies? Instead, we teach adaptability, resourcefulness, the meta-skill of figuring things out independently. Our students don't memorize syntax—they learn to read documentation, to debug systematically, to break complex problems into manageable pieces. They learn that being stuck is normal, that frustration is part of the process, that the answer is always findable if you're persistent enough. These lessons apply far beyond coding. They're life skills, survival skills for the 21st century. And once you've truly learned how to learn, nothing can stop you."
];

try 
{
    $pdo = Database::getConnection();
    $userModel = new UserModel();

    // === CRÉATION DES 3 USERS ===
    
    $users = [
        'Wayl' => [
            'fullname' => 'Wayl Louaked',
            'pass' => getenv('ADMIN_USER_PASS'),
            'email' => getenv('ADMIN_USER_EMAIL')
        ],
        'Xavier' => [
            'fullname' => 'Xavier Niel',
            'pass' => getenv('DEMO1_USER_PASS'),
            'email' => getenv('DEMO1_USER_EMAIL')
        ],
        'Ecole42' => [
            'fullname' => 'Ecole 42',
            'pass' => getenv('DEMO2_USER_PASS'),
            'email' => getenv('DEMO2_USER_EMAIL')
        ]
    ];

    $userIds = [];

    foreach ($users as $username => $data) {
        if (!$userModel->usernameExists($username)) {
            $created = $userModel->create($username, $data['fullname'], $data['email'], $data['pass'], '000000');
            if ($created) {
                $pdo->prepare("UPDATE users SET validated = 1, validation_code = NULL WHERE username = ?")
                    ->execute([$username]);
            }
        }
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $userIds[$username] = $stmt->fetchColumn();
    }

    // === CRÉATION DES POSTS DANS L'ORDRE VOULU ===

    // Vérifier si des posts existent déjà pour ces users
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE user_id IN (?, ?, ?)");
    $stmt->execute([$userIds['Wayl'], $userIds['Xavier'], $userIds['Ecole42']]);
    $totalPosts = $stmt->fetchColumn();

    if ($totalPosts == 0) {

        // Ordre de base
        $baseOrder = [
            ['user' => 'Xavier', 'file' => 'xavier_niel4.png', 'caption' => XAVIER_CAPTIONS[4]],
            ['user' => 'Wayl', 'file' => 'demo1.png', 'caption' => CAPTIONS[1]],
            ['user' => 'Wayl', 'file' => 'demo3.png', 'caption' => CAPTIONS[3]],
            ['user' => 'Ecole42', 'file' => 'ecole42_4.png', 'caption' => ECOLE42_CAPTIONS[4]],
            ['user' => 'Wayl', 'file' => 'demo10.png', 'caption' => CAPTIONS[10]],
            ['user' => 'Wayl', 'file' => 'demo12.png', 'caption' => CAPTIONS[12]],
            ['user' => 'Ecole42', 'file' => 'ecole42_5.png', 'caption' => ECOLE42_CAPTIONS[5]],
            ['user' => 'Wayl', 'file' => 'demo9.png', 'caption' => CAPTIONS[9]],
            ['user' => 'Wayl', 'file' => 'demo8.png', 'caption' => CAPTIONS[8]],
            ['user' => 'Ecole42', 'file' => 'ecole42_3.png', 'caption' => ECOLE42_CAPTIONS[3]],
        ];

        // Le reste des posts à mélanger
        $remainingPosts = [
            ['user' => 'Xavier', 'file' => 'xavier_niel2.png', 'caption' => XAVIER_CAPTIONS[2]],
            ['user' => 'Ecole42', 'file' => 'ecole42_1.png', 'caption' => ECOLE42_CAPTIONS[1]],
            ['user' => 'Ecole42', 'file' => 'ecole42_2.png', 'caption' => ECOLE42_CAPTIONS[2]],
            ['user' => 'Wayl', 'file' => 'demo2.png', 'caption' => CAPTIONS[2]],
            ['user' => 'Wayl', 'file' => 'demo4.png', 'caption' => CAPTIONS[4]],
            ['user' => 'Wayl', 'file' => 'demo5.png', 'caption' => CAPTIONS[5]],
            ['user' => 'Wayl', 'file' => 'demo6.png', 'caption' => CAPTIONS[6]],
            ['user' => 'Wayl', 'file' => 'demo7.png', 'caption' => CAPTIONS[7]],
            ['user' => 'Wayl', 'file' => 'demo11.png', 'caption' => CAPTIONS[11]],
            ['user' => 'Wayl', 'file' => 'demo13.png', 'caption' => CAPTIONS[13]],
            ['user' => 'Wayl', 'file' => 'demo14.png', 'caption' => CAPTIONS[14]],
            ['user' => 'Wayl', 'file' => 'demo15.png', 'caption' => CAPTIONS[15]],
        ];

        // Mélanger le reste
        shuffle($remainingPosts);

        // Fusionner l'ordre de base + le reste mélangé
        $allPosts = array_merge($baseOrder, $remainingPosts);

        // Insérer tous les posts
        foreach ($allPosts as $post) {
            $sourcePath = __DIR__ . "/../assets/images/placeholders/" . $post['file'];

            if (file_exists($sourcePath)) {
                $ext = pathinfo($post['file'], PATHINFO_EXTENSION);
                $newFileName = "post_" . bin2hex(random_bytes(6)) . "." . $ext;
                $destPath = __DIR__ . '/../public/uploads/posts/' . $newFileName;

                if (copy($sourcePath, $destPath)) {
                    $sql = "INSERT INTO posts (user_id, image_path, caption) VALUES (:uid, :path, :cap)";
                    $pdo->prepare($sql)->execute([
                        ':uid'  => $userIds[$post['user']],
                        ':path' => 'public/uploads/posts/' . $newFileName,
                        ':cap'  => $post['caption']
                    ]);
                }
            }
        }
    }
}
catch (Exception $e) {}