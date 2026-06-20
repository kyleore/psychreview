<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\QuizQuestion;
use App\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PsychReviewSeeder extends Seeder
{
    public function run(): void
    {
        // Idempotent: if the content is already seeded, do nothing.
        // This lets db:seed run safely on every boot without wiping or
        // duplicating data on a persistent database.
        if (Category::count() > 0) {
            return;
        }

        // Categories aligned to the PRC Psychometrician Licensure Exam coverage
        // (Theories of Personality 20%, Psychological Assessment 45%,
        //  Abnormal 20%, Industrial/Organizational 15%) plus foundation subjects.
        $categories = [
            ['name' => 'Theories of Personality',            'icon' => 'fingerprint',    'color' => 'fuchsia'],
            ['name' => 'Psychological Assessment',           'icon' => 'clipboard-list', 'color' => 'indigo'],
            ['name' => 'Abnormal Psychology',                'icon' => 'heart-pulse',    'color' => 'rose'],
            ['name' => 'Industrial/Organizational Psych',    'icon' => 'briefcase',      'color' => 'amber'],
            ['name' => 'General Psychology',                 'icon' => 'brain-circuit',  'color' => 'violet'],
            ['name' => 'Developmental Psychology',           'icon' => 'baby',           'color' => 'emerald'],
            ['name' => 'Social Psychology',                  'icon' => 'users',          'color' => 'sky'],
            ['name' => 'Ethics & PH Psychology Law',         'icon' => 'scale',          'color' => 'cyan'],

            // ───── Psychologist Licensure Exam (BLEPP) advanced subjects ─────
            ['name' => 'Advanced Psychological Assessment',  'icon' => 'gauge',          'color' => 'teal'],
            ['name' => 'Counseling and Psychotherapy',       'icon' => 'heart-handshake','color' => 'pink'],
            ['name' => 'Advanced Abnormal Psychology',       'icon' => 'brain',          'color' => 'orange'],
            ['name' => 'Advanced Theories of Personality',   'icon' => 'venetian-mask',  'color' => 'lime'],
        ];

        $catModels = [];
        foreach ($categories as $c) {
            $catModels[$c['name']] = Category::create([
                'name' => $c['name'],
                'slug' => Str::slug($c['name']),
                'icon' => $c['icon'],
                'color' => $c['color'],
            ]);
        }

        $topics = [
            // ───────────── Theories of Personality ─────────────
            [
                'cat' => 'Theories of Personality', 'title' => "Freud's Psychoanalytic Theory", 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Personality is shaped by unconscious drives and early childhood experiences, organized into the id, ego, and superego.',
                'key_points' => 'Id = pleasure principle; Ego = reality principle; Superego = morality/conscience. Defense mechanisms: repression, denial, projection, displacement, sublimation. Psychosexual stages: oral, anal, phallic (Oedipus/Electra), latency, genital.',
                'example' => 'An employee who is angry at the boss but yells at a sibling instead is using displacement.',
                'q' => 'Which Freudian structure operates on the reality principle?',
                'options' => ['Id', 'Ego', 'Superego', 'Libido'], 'correct' => 1,
                'exp' => 'The ego operates on the reality principle, mediating between the id and the superego.',
            ],
            [
                'cat' => 'Theories of Personality', 'title' => "Erikson's Psychosocial Stages", 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Eight lifespan stages, each with a psychosocial crisis that must be resolved for healthy personality development.',
                'key_points' => 'Trust vs. Mistrust, Autonomy vs. Shame/Doubt, Initiative vs. Guilt, Industry vs. Inferiority, Identity vs. Role Confusion, Intimacy vs. Isolation, Generativity vs. Stagnation, Ego Integrity vs. Despair.',
                'example' => 'A teenager trying out different friend groups and beliefs is resolving Identity vs. Role Confusion.',
                'q' => 'The psychosocial crisis of adolescence is:',
                'options' => ['Trust vs. Mistrust', 'Identity vs. Role Confusion', 'Intimacy vs. Isolation', 'Integrity vs. Despair'], 'correct' => 1,
                'exp' => 'Adolescence centers on Identity vs. Role Confusion.',
            ],
            [
                'cat' => 'Theories of Personality', 'title' => 'The Big Five Personality Traits (OCEAN)', 'difficulty' => 'BEGINNER',
                'definition' => 'A trait model describing five broad dimensions: Openness, Conscientiousness, Extraversion, Agreeableness, and Neuroticism.',
                'key_points' => 'Each dimension is a continuum and is relatively stable across the lifespan. Commonly measured by inventories such as the NEO-PI-R.',
                'example' => 'An employee high in Conscientiousness is dependable, organized, and consistently meets deadlines.',
                'q' => 'Which of the following is NOT one of the Big Five traits?',
                'options' => ['Openness', 'Neuroticism', 'Dominance', 'Agreeableness'], 'correct' => 2,
                'exp' => 'Dominance is not part of the Big Five (OCEAN).',
            ],
            [
                'cat' => 'Theories of Personality', 'title' => 'Humanistic Theory (Maslow & Rogers)', 'difficulty' => 'BEGINNER',
                'definition' => 'Emphasizes free will, personal growth, and self-actualization rather than unconscious conflict or conditioning.',
                'key_points' => "Maslow's hierarchy: physiological → safety → love/belonging → esteem → self-actualization. Rogers: self-concept, congruence, and unconditional positive regard produce a fully functioning person.",
                'example' => 'A counselor who shows unconditional positive regard helps a client feel accepted and grow.',
                'q' => '"Unconditional positive regard" is a key concept of:',
                'options' => ['Sigmund Freud', 'B. F. Skinner', 'Carl Rogers', 'Albert Bandura'], 'correct' => 2,
                'exp' => 'Carl Rogers introduced unconditional positive regard in person-centered therapy.',
            ],
            [
                'cat' => 'Theories of Personality', 'title' => 'Trait Theories (Allport, Cattell, Eysenck)', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Approaches that describe personality through measurable, enduring traits.',
                'key_points' => 'Allport: cardinal, central, and secondary traits. Cattell: 16 source traits (16PF) via factor analysis. Eysenck (PEN): Extraversion, Neuroticism, and Psychoticism.',
                'example' => 'Cattell used factor analysis to derive the 16 personality factors measured by the 16PF.',
                'q' => 'The 16 Personality Factor (16PF) questionnaire was developed by:',
                'options' => ['Gordon Allport', 'Raymond Cattell', 'Hans Eysenck', 'Carl Jung'], 'correct' => 1,
                'exp' => 'Raymond Cattell developed the 16PF.',
            ],

            // ───────────── Psychological Assessment ─────────────
            [
                'cat' => 'Psychological Assessment', 'title' => 'Reliability', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'The consistency or stability of test scores across time, items, or raters.',
                'key_points' => "Types: test-retest, parallel/alternate forms, internal consistency (split-half, Cronbach's alpha, KR-20), and inter-rater. Reliability is necessary but not sufficient for validity.",
                'example' => 'A test that yields nearly the same score when retaken a week later has high test-retest reliability.',
                'q' => "Cronbach's alpha is a measure of which type of reliability?",
                'options' => ['Test-retest', 'Internal consistency', 'Inter-rater', 'Predictive'], 'correct' => 1,
                'exp' => "Cronbach's alpha estimates internal consistency reliability.",
            ],
            [
                'cat' => 'Psychological Assessment', 'title' => 'Validity', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'The degree to which a test measures what it claims to measure and supports the intended interpretation of scores.',
                'key_points' => 'Content validity, criterion-related validity (concurrent & predictive), and construct validity (convergent & discriminant). A test can be reliable yet not valid.',
                'example' => 'Using entrance-exam scores to predict future college GPA demonstrates predictive (criterion) validity.',
                'q' => 'A test that accurately predicts future job performance demonstrates which validity?',
                'options' => ['Content', 'Predictive', 'Face', 'Construct'], 'correct' => 1,
                'exp' => 'Predicting a future criterion is predictive (criterion-related) validity.',
            ],
            [
                'cat' => 'Psychological Assessment', 'title' => 'Levels of Measurement', 'difficulty' => 'BEGINNER',
                'definition' => "Stevens' four scales that classify data: nominal, ordinal, interval, and ratio.",
                'key_points' => 'Nominal = categories/labels; Ordinal = ranked order; Interval = equal intervals with no true zero (e.g., temperature, IQ); Ratio = equal intervals with a true zero (e.g., weight, reaction time).',
                'example' => 'Ranking students as 1st, 2nd, and 3rd place is ordinal data.',
                'q' => 'Temperature measured in Celsius is an example of which scale?',
                'options' => ['Nominal', 'Ordinal', 'Interval', 'Ratio'], 'correct' => 2,
                'exp' => 'Celsius temperature has equal intervals but no true zero, making it interval.',
            ],
            [
                'cat' => 'Psychological Assessment', 'title' => 'Norms and Standard Scores', 'difficulty' => 'INTERMEDIATE',
                'definition' => "Reference data and transformed scores used to interpret an individual's raw score relative to a comparison group.",
                'key_points' => 'Percentiles; z-scores (M = 0, SD = 1); T-scores (M = 50, SD = 10); stanines; deviation IQ (M = 100, SD = 15). The standardization sample must be representative.',
                'example' => 'A z-score of +1.0 means the score is one standard deviation above the mean.',
                'q' => 'A T-score has a mean and standard deviation of:',
                'options' => ['0 and 1', '50 and 10', '100 and 15', '100 and 10'], 'correct' => 1,
                'exp' => 'T-scores have a mean of 50 and a standard deviation of 10.',
            ],
            [
                'cat' => 'Psychological Assessment', 'title' => 'Types of Psychological Tests', 'difficulty' => 'BEGINNER',
                'definition' => 'Tests classified by what they measure and how they are administered.',
                'key_points' => 'Intelligence (Stanford-Binet, Wechsler), aptitude, achievement, interest, and personality. Personality tests are objective (e.g., MMPI) or projective (e.g., Rorschach, TAT). Also individual vs. group and speed vs. power tests.',
                'example' => 'The Rorschach Inkblot Test is a projective personality test.',
                'q' => 'The MMPI is an example of a(n) ___ personality test.',
                'options' => ['Projective', 'Objective', 'Aptitude', 'Achievement'], 'correct' => 1,
                'exp' => 'The MMPI uses structured items, making it an objective personality test.',
            ],
            [
                'cat' => 'Psychological Assessment', 'title' => 'Item Analysis', 'difficulty' => 'ADVANCED',
                'definition' => 'The statistical evaluation of individual test items to improve a test.',
                'key_points' => 'Item difficulty index (p) = proportion who answered correctly. Item discrimination index = how well an item separates high from low scorers. Distractor analysis evaluates wrong options.',
                'example' => 'An item answered correctly by half of examinees (p = .50) provides maximum discrimination.',
                'q' => 'The item difficulty index (p) represents the ___ of examinees who answered correctly.',
                'options' => ['number', 'proportion', 'variance', 'rank'], 'correct' => 1,
                'exp' => 'Item difficulty is the proportion (0–1) answering an item correctly.',
            ],
            [
                'cat' => 'Psychological Assessment', 'title' => 'Intelligence Testing', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'The measurement of general cognitive ability.',
                'key_points' => "Binet-Simon → Stanford-Binet (Terman introduced IQ). Wechsler scales (WAIS, WISC) use deviation IQ. Ratio IQ = MA/CA × 100. Spearman's g, Gardner's multiple intelligences, Cattell's fluid vs. crystallized intelligence.",
                'example' => 'The WAIS measures adult intelligence using deviation IQ scores.',
                'q' => 'The concept of "g" (general intelligence) was proposed by:',
                'options' => ['Howard Gardner', 'Charles Spearman', 'David Wechsler', 'Alfred Binet'], 'correct' => 1,
                'exp' => 'Charles Spearman proposed the general intelligence factor, "g".',
            ],

            // ───────────── Abnormal Psychology ─────────────
            [
                'cat' => 'Abnormal Psychology', 'title' => 'DSM-5 Classification', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'The standard manual used to diagnose mental disorders using standardized criteria.',
                'key_points' => 'Published by the American Psychiatric Association (APA). Uses categorical diagnoses and removed the older multiaxial system. Distinct from the ICD published by the WHO.',
                'example' => "A clinician checks DSM-5 criteria to determine if a client's symptoms meet Major Depressive Disorder.",
                'q' => 'The DSM-5 is published by the:',
                'options' => ['World Health Organization', 'American Psychiatric Association', 'American Psychological Association', 'PRC Board of Psychology'], 'correct' => 1,
                'exp' => 'The American Psychiatric Association publishes the DSM-5.',
            ],
            [
                'cat' => 'Abnormal Psychology', 'title' => 'Anxiety Disorders', 'difficulty' => 'BEGINNER',
                'definition' => 'Disorders marked by excessive fear, anxiety, and related behavioral disturbances.',
                'key_points' => 'Generalized Anxiety Disorder, Panic Disorder, Specific Phobia, Social Anxiety Disorder, and Agoraphobia. Fear is a response to a present threat; anxiety anticipates a future threat.',
                'example' => 'A person with a specific phobia feels intense fear of a particular object, such as spiders.',
                'q' => 'Recurrent, unexpected panic attacks are the hallmark of:',
                'options' => ['Generalized Anxiety Disorder', 'Panic Disorder', 'OCD', 'PTSD'], 'correct' => 1,
                'exp' => 'Panic Disorder is defined by recurrent unexpected panic attacks.',
            ],
            [
                'cat' => 'Abnormal Psychology', 'title' => 'Mood Disorders', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Disorders involving disturbances in emotion, ranging from depression to mania.',
                'key_points' => 'Major Depressive Disorder (≥2 weeks of low mood/anhedonia), Persistent Depressive Disorder (dysthymia), Bipolar I (full mania) vs. Bipolar II (hypomania plus depression).',
                'example' => 'Alternating episodes of full mania and depression point to Bipolar I Disorder.',
                'q' => 'A full manic episode is required to diagnose:',
                'options' => ['Major Depressive Disorder', 'Bipolar I Disorder', 'Dysthymia', 'Cyclothymia'], 'correct' => 1,
                'exp' => 'Bipolar I requires at least one full manic episode.',
            ],
            [
                'cat' => 'Abnormal Psychology', 'title' => 'Schizophrenia Spectrum', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Disorders involving psychosis — distortions in thinking, perception, and behavior.',
                'key_points' => 'Positive symptoms: hallucinations, delusions, disorganized speech. Negative symptoms: flat affect, avolition, alogia. Symptoms persist ≥6 months. Linked to the dopamine hypothesis.',
                'example' => "Hearing voices that aren't there is an auditory hallucination, a positive symptom.",
                'q' => 'Flat affect and avolition are classified as ___ symptoms.',
                'options' => ['positive', 'negative', 'cognitive', 'manic'], 'correct' => 1,
                'exp' => 'Flat affect and avolition are negative symptoms of schizophrenia.',
            ],
            [
                'cat' => 'Abnormal Psychology', 'title' => 'Personality Disorders', 'difficulty' => 'ADVANCED',
                'definition' => 'Enduring, inflexible patterns of inner experience and behavior that deviate markedly from cultural expectations.',
                'key_points' => 'Cluster A (odd/eccentric): paranoid, schizoid, schizotypal. Cluster B (dramatic/erratic): antisocial, borderline, histrionic, narcissistic. Cluster C (anxious/fearful): avoidant, dependent, obsessive-compulsive PD.',
                'example' => 'Unstable relationships, impulsivity, and fear of abandonment suggest Borderline PD (Cluster B).',
                'q' => 'Antisocial Personality Disorder belongs to which cluster?',
                'options' => ['Cluster A', 'Cluster B', 'Cluster C', 'Cluster D'], 'correct' => 1,
                'exp' => 'Antisocial PD is in Cluster B (dramatic/erratic).',
            ],

            // ───────────── Industrial/Organizational ─────────────
            [
                'cat' => 'Industrial/Organizational Psych', 'title' => 'Recruitment and Selection', 'difficulty' => 'BEGINNER',
                'definition' => 'The processes of attracting applicants and choosing the best-fit candidates for jobs.',
                'key_points' => 'Begins with job analysis to identify KSAOs (knowledge, skills, abilities, other characteristics). Selection tools: interviews, tests, work samples, assessment centers. Watch for validity and adverse impact.',
                'example' => 'Using a structured interview plus a cognitive ability test to hire staff is part of selection.',
                'q' => 'Identifying the KSAOs required for a job is the main goal of:',
                'options' => ['Job analysis', 'Performance appraisal', 'Training', 'Onboarding'], 'correct' => 0,
                'exp' => 'Job analysis identifies the KSAOs needed to perform a job.',
            ],
            [
                'cat' => 'Industrial/Organizational Psych', 'title' => 'Work Motivation Theories', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Theories explaining what energizes, directs, and sustains employee behavior.',
                'key_points' => "Maslow's hierarchy; Herzberg's Two-Factor (hygiene vs. motivators); McGregor's Theory X/Y; Vroom's Expectancy (effort→performance→outcome, valence); Adams' Equity; Locke's Goal-Setting.",
                'example' => 'Herzberg argues salary (a hygiene factor) prevents dissatisfaction, while recognition (a motivator) drives satisfaction.',
                'q' => 'The Two-Factor (Motivator-Hygiene) theory was proposed by:',
                'options' => ['Abraham Maslow', 'Frederick Herzberg', 'Victor Vroom', 'Douglas McGregor'], 'correct' => 1,
                'exp' => 'Frederick Herzberg developed the Two-Factor theory.',
            ],
            [
                'cat' => 'Industrial/Organizational Psych', 'title' => 'Leadership Theories', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Frameworks describing what makes leadership effective.',
                'key_points' => 'Trait, Behavioral (Ohio State: initiating structure & consideration), Contingency (Fiedler), Situational (Hersey-Blanchard), and Transactional vs. Transformational leadership.',
                'example' => 'A transformational leader inspires and motivates employees to go beyond self-interest for a shared vision.',
                'q' => 'Inspiring followers toward a shared vision best describes ___ leadership.',
                'options' => ['transactional', 'transformational', 'laissez-faire', 'autocratic'], 'correct' => 1,
                'exp' => 'Transformational leaders inspire followers toward a shared vision.',
            ],
            [
                'cat' => 'Industrial/Organizational Psych', 'title' => 'Performance Appraisal', 'difficulty' => 'BEGINNER',
                'definition' => 'The systematic evaluation of employee job performance.',
                'key_points' => 'Methods: graphic rating scales, BARS, 360-degree feedback, and MBO. Common rater errors: halo, leniency/severity, central tendency, and recency.',
                'example' => 'Rating an employee high on every trait just because they are likeable is a halo error.',
                'q' => 'Rating an employee high on all traits due to one favorable quality is the ___ error.',
                'options' => ['leniency', 'halo', 'recency', 'central tendency'], 'correct' => 1,
                'exp' => 'The halo error lets one trait color all other ratings.',
            ],

            // ───────────── General Psychology ─────────────
            [
                'cat' => 'General Psychology', 'title' => 'Schools of Psychology', 'difficulty' => 'BEGINNER',
                'definition' => 'The historical perspectives that shaped psychology as a science.',
                'key_points' => 'Structuralism (Wundt/Titchener — introspection), Functionalism (James), Psychoanalysis (Freud), Behaviorism (Watson/Skinner), Gestalt (the whole is greater than the parts), Humanism (Maslow/Rogers), and Cognitive.',
                'example' => "Wundt's 1879 laboratory in Leipzig marks the founding of scientific psychology.",
                'q' => 'Who is regarded as the father of modern (experimental) psychology?',
                'options' => ['Sigmund Freud', 'Wilhelm Wundt', 'William James', 'John Watson'], 'correct' => 1,
                'exp' => 'Wilhelm Wundt founded the first psychology laboratory in 1879.',
            ],
            [
                'cat' => 'General Psychology', 'title' => 'Neurons and Neurotransmitters', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Nerve cells and the chemical messengers that transmit signals across synapses.',
                'key_points' => 'Neuron parts: dendrites, soma, axon, terminal buttons; the action potential travels along the axon. Neurotransmitters: dopamine (reward/movement), serotonin (mood), acetylcholine (memory/muscle), GABA (inhibitory), glutamate (excitatory).',
                'example' => 'Low serotonin is linked to depression, which is why SSRIs target serotonin.',
                'q' => 'Which neurotransmitter is primarily inhibitory?',
                'options' => ['Glutamate', 'GABA', 'Dopamine', 'Acetylcholine'], 'correct' => 1,
                'exp' => 'GABA is the main inhibitory neurotransmitter in the brain.',
            ],
            [
                'cat' => 'General Psychology', 'title' => 'Classical Conditioning', 'difficulty' => 'BEGINNER',
                'definition' => 'Learning through association between a neutral stimulus and a meaningful one.',
                'key_points' => 'Discovered by Ivan Pavlov. Terms: US, UR, CS, CR. Processes: acquisition, extinction, spontaneous recovery, generalization, discrimination. Watson\'s "Little Albert" study.',
                'example' => "Pavlov's dogs salivated to a bell that had been repeatedly paired with food.",
                'q' => "In Pavlov's experiment, the food is the:",
                'options' => ['Conditioned stimulus (CS)', 'Conditioned response (CR)', 'Unconditioned stimulus (US)', 'Unconditioned response (UR)'], 'correct' => 2,
                'exp' => 'Food naturally triggers salivation, making it the unconditioned stimulus (US).',
            ],
            [
                'cat' => 'General Psychology', 'title' => 'Operant Conditioning', 'difficulty' => 'BEGINNER',
                'definition' => 'Learning in which behavior is controlled by its consequences.',
                'key_points' => 'B. F. Skinner. Reinforcement (positive/negative) increases behavior; punishment (positive/negative) decreases it. Schedules: FR, VR, FI, VI — variable-ratio is most resistant to extinction. Shaping reinforces successive approximations.',
                'example' => 'A child cleans their room to earn screen time — positive reinforcement.',
                'q' => 'Which reinforcement schedule produces the highest, steadiest response rate?',
                'options' => ['Fixed-ratio', 'Variable-ratio', 'Fixed-interval', 'Variable-interval'], 'correct' => 1,
                'exp' => 'Variable-ratio schedules yield high, steady responding (e.g., slot machines).',
            ],
            [
                'cat' => 'General Psychology', 'title' => 'Memory', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'The encoding, storage, and retrieval of information.',
                'key_points' => 'Atkinson-Shiffrin model: sensory → short-term/working memory (7 ± 2 items, Miller) → long-term (explicit/declarative & implicit/procedural). Forgetting via decay and interference. Serial position effect (primacy & recency).',
                'example' => 'Silently repeating a phone number to hold it in short-term memory is maintenance rehearsal.',
                'q' => 'The capacity of short-term memory is about:',
                'options' => ['3 ± 1 items', '7 ± 2 items', 'unlimited', '12 items'], 'correct' => 1,
                'exp' => "Miller's magic number is 7 ± 2 items.",
            ],

            // ───────────── Developmental ─────────────
            [
                'cat' => 'Developmental Psychology', 'title' => "Piaget's Cognitive Stages", 'difficulty' => 'BEGINNER',
                'definition' => "A theory of four stages of children's intellectual development.",
                'key_points' => 'Sensorimotor (0–2, object permanence), Preoperational (2–7, egocentrism, symbolic thought), Concrete Operational (7–11, conservation), Formal Operational (11+, abstract reasoning). Assimilation vs. accommodation.',
                'example' => 'Knowing a toy still exists when hidden under a blanket shows object permanence.',
                'q' => 'Conservation is typically mastered in which stage?',
                'options' => ['Sensorimotor', 'Preoperational', 'Concrete Operational', 'Formal Operational'], 'correct' => 2,
                'exp' => 'Conservation develops during the Concrete Operational stage.',
            ],
            [
                'cat' => 'Developmental Psychology', 'title' => "Kohlberg's Moral Development", 'difficulty' => 'INTERMEDIATE',
                'definition' => 'A theory of three levels (six stages) of moral reasoning.',
                'key_points' => 'Preconventional (obedience/punishment, self-interest), Conventional (good boy/girl, law-and-order), Postconventional (social contract, universal ethical principles). Studied using the Heinz dilemma.',
                'example' => 'Obeying a rule only to avoid punishment reflects preconventional morality.',
                'q' => '"Law and order" reasoning belongs to which level?',
                'options' => ['Preconventional', 'Conventional', 'Postconventional', 'Amoral'], 'correct' => 1,
                'exp' => 'Law-and-order orientation is part of the Conventional level.',
            ],
            [
                'cat' => 'Developmental Psychology', 'title' => 'Attachment Theory', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'The study of the emotional bond between an infant and caregiver.',
                'key_points' => "Bowlby's attachment theory. Ainsworth's Strange Situation identifies secure, insecure-avoidant, and insecure-resistant/ambivalent (later disorganized) styles. Harlow's monkeys showed the importance of contact comfort.",
                'example' => 'In the Strange Situation, a securely attached infant explores freely and is comforted upon reunion.',
                'q' => "Ainsworth's \"Strange Situation\" procedure assesses:",
                'options' => ['Intelligence', 'Attachment style', 'Temperament', 'Moral reasoning'], 'correct' => 1,
                'exp' => 'The Strange Situation classifies infant attachment styles.',
            ],

            // ───────────── Social ─────────────
            [
                'cat' => 'Social Psychology', 'title' => 'Cognitive Dissonance', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'The mental discomfort experienced when holding two or more contradictory beliefs, values, or attitudes at once.',
                'key_points' => 'Proposed by Leon Festinger (1957). People reduce dissonance by changing a belief, adding new cognitions, or minimizing the conflict.',
                'example' => 'A smoker who knows smoking is harmful may downplay the risks to ease the discomfort.',
                'q' => 'Cognitive dissonance theory was proposed by:',
                'options' => ['Leon Festinger', 'Stanley Milgram', 'Solomon Asch', 'Philip Zimbardo'], 'correct' => 0,
                'exp' => 'Leon Festinger proposed cognitive dissonance theory in 1957.',
            ],
            [
                'cat' => 'Social Psychology', 'title' => 'Conformity and Obedience', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Changing behavior due to group pressure (conformity) or the commands of an authority (obedience).',
                'key_points' => "Asch's line-judgment study (conformity). Milgram's obedience study (≈65% delivered the maximum shock). Zimbardo's Stanford Prison Experiment (situational power and social roles).",
                'example' => "Going along with a clearly wrong group answer reflects normative conformity, as in Asch's study.",
                'q' => "Milgram's classic study primarily demonstrated:",
                'options' => ['Conformity', 'Obedience to authority', 'The bystander effect', 'Groupthink'], 'correct' => 1,
                'exp' => 'Milgram studied obedience to authority figures.',
            ],

            // ───────────── Ethics & PH Law ─────────────
            [
                'cat' => 'Ethics & PH Psychology Law', 'title' => 'RA 10029 — Philippine Psychology Act of 2009', 'difficulty' => 'BEGINNER',
                'definition' => 'The Philippine law that regulates the practice of psychology and psychometrics in the country.',
                'key_points' => 'Created the Professional Regulatory Board of Psychology under the PRC. Defines and licenses Psychologists and Psychometricians and sets the requirements for the licensure examinations.',
                'example' => 'A psychology graduate must pass the PRC licensure exam under RA 10029 to practice as a Psychometrician.',
                'q' => 'RA 10029 is also known as the:',
                'options' => ['Mental Health Act', 'Philippine Psychology Act of 2009', 'Data Privacy Act', 'Magna Carta for Students'], 'correct' => 1,
                'exp' => 'RA 10029 is the Philippine Psychology Act of 2009.',
            ],
            [
                'cat' => 'Ethics & PH Psychology Law', 'title' => 'Scope: Psychometrician vs. Psychologist', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'The distinct functions licensed under RA 10029 for psychometricians and psychologists.',
                'key_points' => "A Psychometrician (bachelor's in psychology) may administer and score tests, conduct intake interviews, and interpret results under supervision. A Psychologist (master's) may perform psychological testing including projectives, diagnosis, and psychotherapy/intervention.",
                'example' => 'A Psychometrician may administer and score a test, but a licensed Psychologist interprets projective tests and provides therapy.',
                'q' => 'Which task may a Psychometrician NOT do independently?',
                'options' => ['Administer tests', 'Score tests', 'Conduct psychotherapy', 'Do intake interviews'], 'correct' => 2,
                'exp' => 'Psychotherapy/intervention is reserved for licensed Psychologists.',
            ],
            [
                'cat' => 'Ethics & PH Psychology Law', 'title' => 'Professional Code of Ethics', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'The ethical standards governing Filipino psychologists and psychometricians.',
                'key_points' => 'Core principles: confidentiality, informed consent, competence, integrity, and avoiding harm. Proper test use and data protection (also covered by RA 10173, the Data Privacy Act).',
                'example' => "Keeping a client's test results private unless consent is given upholds confidentiality.",
                'q' => "Obtaining a client's voluntary agreement before testing is called:",
                'options' => ['Confidentiality', 'Informed consent', 'Debriefing', 'Standardization'], 'correct' => 1,
                'exp' => "Informed consent is the client's voluntary, informed agreement to participate.",
            ],

            // ═══════════ ADDITIONAL TOPICS ═══════════

            // ───────────── Theories of Personality (more) ─────────────
            [
                'cat' => 'Theories of Personality', 'title' => "Jung's Analytical Psychology", 'difficulty' => 'INTERMEDIATE',
                'definition' => "Carl Jung's theory emphasizing the collective unconscious, archetypes, and the lifelong process of individuation toward wholeness.",
                'key_points' => 'Personal unconscious vs. collective unconscious. Archetypes: persona, shadow, anima/animus, self. Introversion vs. extraversion. Individuation = integrating all parts of the psyche.',
                'example' => "A person projecting their hidden, unacceptable traits onto others is expressing the shadow archetype.",
                'q' => 'The "collective unconscious" and archetypes were proposed by:',
                'options' => ['Sigmund Freud', 'Carl Jung', 'Alfred Adler', 'Karen Horney'], 'correct' => 1,
                'exp' => 'Carl Jung introduced the collective unconscious and archetypes.',
            ],
            [
                'cat' => 'Theories of Personality', 'title' => "Adler's Individual Psychology", 'difficulty' => 'INTERMEDIATE',
                'definition' => "Alfred Adler's theory that humans are driven by social interest and the striving for superiority to overcome feelings of inferiority.",
                'key_points' => 'Inferiority complex and compensation. Striving for superiority. Style of life. Social interest (Gemeinschaftsgefühl). Birth order influences personality.',
                'example' => 'A child who feels weak may overcompensate by excelling in academics — striving for superiority.',
                'q' => 'The concept of the "inferiority complex" was developed by:',
                'options' => ['Carl Jung', 'Alfred Adler', 'Erik Erikson', 'Gordon Allport'], 'correct' => 1,
                'exp' => 'Alfred Adler developed the inferiority complex concept.',
            ],
            [
                'cat' => 'Theories of Personality', 'title' => "Bandura's Social Cognitive Theory", 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Personality develops through the interaction of behavior, personal factors, and the environment, with learning occurring by observation.',
                'key_points' => 'Reciprocal determinism (person ↔ behavior ↔ environment). Observational learning/modeling. Self-efficacy. The Bobo doll experiment demonstrated learning aggression by imitation.',
                'example' => 'A student who believes they can pass the board exam (high self-efficacy) studies harder and performs better.',
                'q' => 'The belief in one\'s ability to succeed at a task is called:',
                'options' => ['Self-esteem', 'Self-efficacy', 'Self-concept', 'Self-actualization'], 'correct' => 1,
                'exp' => 'Self-efficacy is the belief in one\'s capability to perform a task, per Bandura.',
            ],

            // ───────────── Psychological Assessment (more) ─────────────
            [
                'cat' => 'Psychological Assessment', 'title' => 'Measures of Central Tendency and Variability', 'difficulty' => 'BEGINNER',
                'definition' => 'Descriptive statistics that summarize a distribution of scores.',
                'key_points' => 'Central tendency: mean (average), median (middle), mode (most frequent). Variability: range, variance, and standard deviation. The mean is most affected by outliers.',
                'example' => 'In a skewed income distribution, the median is a better measure of central tendency than the mean.',
                'q' => 'Which measure of central tendency is most affected by extreme scores?',
                'options' => ['Mode', 'Median', 'Mean', 'Range'], 'correct' => 2,
                'exp' => 'The mean is most affected by outliers/extreme scores.',
            ],
            [
                'cat' => 'Psychological Assessment', 'title' => 'Correlation', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'A statistical measure of the strength and direction of the relationship between two variables.',
                'key_points' => 'Coefficient r ranges from -1.0 to +1.0. Positive, negative, and zero correlation. Correlation does NOT imply causation. Pearson r (interval/ratio) vs. Spearman rho (ordinal).',
                'example' => 'More hours of study correlating with higher scores is a positive correlation.',
                'q' => 'A correlation coefficient of -0.85 indicates a relationship that is:',
                'options' => ['Weak and positive', 'Strong and negative', 'Strong and positive', 'No relationship'], 'correct' => 1,
                'exp' => 'A value near -1.0 indicates a strong negative (inverse) relationship.',
            ],
            [
                'cat' => 'Psychological Assessment', 'title' => 'The Normal Distribution', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'A symmetric, bell-shaped distribution where the mean, median, and mode are equal.',
                'key_points' => 'Empirical (68-95-99.7) rule: ~68% fall within ±1 SD, ~95% within ±2 SD, ~99.7% within ±3 SD. Many psychological traits are normally distributed. Skewness describes asymmetry.',
                'example' => 'IQ scores follow a normal distribution with a mean of 100 and SD of 15.',
                'q' => 'In a normal distribution, about what percentage of scores fall within ±1 standard deviation?',
                'options' => ['50%', '68%', '95%', '99.7%'], 'correct' => 1,
                'exp' => 'About 68% of scores fall within one standard deviation of the mean.',
            ],

            // ───────────── Abnormal Psychology (more) ─────────────
            [
                'cat' => 'Abnormal Psychology', 'title' => 'Obsessive-Compulsive and Related Disorders', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Disorders characterized by obsessions (intrusive thoughts) and/or compulsions (repetitive behaviors).',
                'key_points' => 'OCD: obsessions cause anxiety; compulsions reduce it. Related: body dysmorphic disorder, hoarding disorder, trichotillomania (hair-pulling), excoriation (skin-picking).',
                'example' => 'Repeatedly washing hands to relieve the fear of contamination is a compulsion in OCD.',
                'q' => 'In OCD, repetitive behaviors performed to reduce anxiety are called:',
                'options' => ['Obsessions', 'Compulsions', 'Delusions', 'Phobias'], 'correct' => 1,
                'exp' => 'Compulsions are the repetitive behaviors done to neutralize obsessions.',
            ],
            [
                'cat' => 'Abnormal Psychology', 'title' => 'Trauma- and Stressor-Related Disorders', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Disorders that develop following exposure to a traumatic or highly stressful event.',
                'key_points' => 'PTSD: intrusion (flashbacks), avoidance, negative mood/cognition, hyperarousal lasting >1 month. Acute Stress Disorder (3 days–1 month). Adjustment disorders.',
                'example' => 'A survivor of a typhoon who has recurrent flashbacks and hypervigilance may have PTSD.',
                'q' => 'Flashbacks, avoidance, and hyperarousal after a traumatic event characterize:',
                'options' => ['Panic Disorder', 'PTSD', 'OCD', 'GAD'], 'correct' => 1,
                'exp' => 'These are the core symptom clusters of Post-Traumatic Stress Disorder.',
            ],
            [
                'cat' => 'Abnormal Psychology', 'title' => 'Neurodevelopmental Disorders', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'A group of conditions with onset in the developmental period that affect personal, social, and academic functioning.',
                'key_points' => 'Includes ADHD (inattention/hyperactivity), Autism Spectrum Disorder (social-communication deficits, restricted/repetitive behaviors), Intellectual Disability, and Specific Learning Disorders.',
                'example' => 'A child with persistent inattention, hyperactivity, and impulsivity across settings may have ADHD.',
                'q' => 'Deficits in social communication plus restricted, repetitive behaviors define:',
                'options' => ['ADHD', 'Autism Spectrum Disorder', 'Intellectual Disability', 'Dyslexia'], 'correct' => 1,
                'exp' => 'These are the two core criteria of Autism Spectrum Disorder.',
            ],
            [
                'cat' => 'Abnormal Psychology', 'title' => 'Models of Abnormality', 'difficulty' => 'BEGINNER',
                'definition' => 'The major perspectives used to explain the causes of psychological disorders.',
                'key_points' => 'Biological (genetics, neurochemistry), Psychodynamic (unconscious conflict), Behavioral (learning), Cognitive (faulty thinking), Humanistic, and Sociocultural. The biopsychosocial model integrates all. The "4 Ds": Deviance, Distress, Dysfunction, Danger.',
                'example' => 'Explaining depression through low serotonin reflects the biological model.',
                'q' => 'The integrated model considering biological, psychological, and social factors is the:',
                'options' => ['Medical model', 'Biopsychosocial model', 'Behavioral model', 'Cognitive model'], 'correct' => 1,
                'exp' => 'The biopsychosocial model integrates all three domains.',
            ],

            // ───────────── Industrial/Organizational (more) ─────────────
            [
                'cat' => 'Industrial/Organizational Psych', 'title' => 'Job Analysis', 'difficulty' => 'BEGINNER',
                'definition' => 'The systematic process of determining the tasks, duties, and the KSAOs required for a job.',
                'key_points' => 'Outputs: job description (duties) and job specification (KSAOs). Methods: interviews, observation, questionnaires (e.g., PAQ). Foundation for selection, training, and appraisal.',
                'example' => 'Listing the skills and duties for a "HR Assistant" role before hiring is job analysis.',
                'q' => 'A job specification primarily describes the:',
                'options' => ['Duties of the job', 'Salary range', 'KSAOs needed by the worker', 'Company history'], 'correct' => 2,
                'exp' => 'A job specification lists the worker characteristics (KSAOs) required.',
            ],
            [
                'cat' => 'Industrial/Organizational Psych', 'title' => 'Training and Development', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Planned efforts to help employees acquire job-related knowledge, skills, and attitudes.',
                'key_points' => 'ADDIE model: Analysis, Design, Development, Implementation, Evaluation. Needs assessment first. Kirkpatrick\'s 4 levels of evaluation: reaction, learning, behavior, results. Transfer of training.',
                'example' => 'A company runs a needs assessment before designing a customer-service training program.',
                'q' => "Kirkpatrick's model is used to evaluate:",
                'options' => ['Job satisfaction', 'Training effectiveness', 'Leadership style', 'Personality'], 'correct' => 1,
                'exp' => "Kirkpatrick's four levels evaluate training effectiveness.",
            ],
            [
                'cat' => 'Industrial/Organizational Psych', 'title' => 'Job Satisfaction and Commitment', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Employees\' attitudes toward their jobs (satisfaction) and their attachment to the organization (commitment).',
                'key_points' => 'Measured by tools like the JDI and MSQ. Three components of commitment (Meyer & Allen): affective, continuance, normative. Low satisfaction links to turnover and absenteeism.',
                'example' => 'An employee who stays because they would lose benefits shows continuance commitment.',
                'q' => 'Staying with an organization because leaving would be costly is ___ commitment.',
                'options' => ['Affective', 'Continuance', 'Normative', 'Behavioral'], 'correct' => 1,
                'exp' => 'Continuance commitment is based on the perceived costs of leaving.',
            ],

            // ───────────── General Psychology (more) ─────────────
            [
                'cat' => 'General Psychology', 'title' => 'Research Methods in Psychology', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'The systematic approaches used to study behavior and mental processes scientifically.',
                'key_points' => 'Experimental (IV, DV, control vs. experimental groups, random assignment) vs. non-experimental (correlational, observational, survey, case study). Independent variable is manipulated; dependent variable is measured. Confounding variables threaten validity.',
                'example' => 'Manipulating amount of sleep (IV) to measure its effect on memory scores (DV) is an experiment.',
                'q' => 'The variable that is manipulated by the researcher is the:',
                'options' => ['Dependent variable', 'Independent variable', 'Confounding variable', 'Control variable'], 'correct' => 1,
                'exp' => 'The independent variable is the one the experimenter manipulates.',
            ],
            [
                'cat' => 'General Psychology', 'title' => 'Sensation and Perception', 'difficulty' => 'BEGINNER',
                'definition' => 'Sensation is detecting stimuli; perception is organizing and interpreting them.',
                'key_points' => 'Absolute threshold and difference threshold (JND, Weber\'s law). Signal detection theory. Sensory adaptation. Gestalt principles of organization. Bottom-up vs. top-down processing.',
                'example' => 'No longer noticing the smell of a room after a few minutes is sensory adaptation.',
                'q' => 'The minimum difference needed to detect a change between two stimuli is the:',
                'options' => ['Absolute threshold', 'Just noticeable difference', 'Sensory adaptation', 'Transduction'], 'correct' => 1,
                'exp' => 'The just noticeable difference (JND) is the difference threshold.',
            ],
            [
                'cat' => 'General Psychology', 'title' => 'Motivation and Emotion', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Motivation drives goal-directed behavior; emotion is a complex reaction involving feeling, physiology, and expression.',
                'key_points' => 'Drive-reduction, arousal, and incentive theories. Intrinsic vs. extrinsic motivation. Theories of emotion: James-Lange, Cannon-Bard, Schachter-Singer (two-factor). Yerkes-Dodson law of arousal.',
                'example' => 'Feeling your heart race first, then labeling the feeling as fear, reflects the James-Lange theory.',
                'q' => 'Which theory states emotion results from labeling physiological arousal?',
                'options' => ['James-Lange', 'Cannon-Bard', 'Schachter-Singer two-factor', 'Yerkes-Dodson'], 'correct' => 2,
                'exp' => 'The Schachter-Singer two-factor theory combines arousal with cognitive labeling.',
            ],
            [
                'cat' => 'General Psychology', 'title' => 'The Brain and Nervous System', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'The biological structures that control behavior and mental processes.',
                'key_points' => 'CNS (brain + spinal cord) and PNS (somatic + autonomic). Autonomic = sympathetic (fight/flight) and parasympathetic (rest/digest). Brain regions: hindbrain (medulla, cerebellum), midbrain, forebrain (cortex, limbic system). Four lobes: frontal, parietal, temporal, occipital.',
                'example' => 'The amygdala in the limbic system is central to processing fear.',
                'q' => 'Which division of the autonomic nervous system triggers the "fight-or-flight" response?',
                'options' => ['Parasympathetic', 'Sympathetic', 'Somatic', 'Central'], 'correct' => 1,
                'exp' => 'The sympathetic nervous system activates fight-or-flight.',
            ],

            // ───────────── Developmental (more) ─────────────
            [
                'cat' => 'Developmental Psychology', 'title' => "Vygotsky's Sociocultural Theory", 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Cognitive development is driven by social interaction and culture.',
                'key_points' => 'Zone of Proximal Development (ZPD): the gap between what a learner can do alone vs. with guidance. Scaffolding. The More Knowledgeable Other (MKO). Private speech guides thinking.',
                'example' => 'A teacher giving just enough hints for a student to solve a problem is scaffolding within the ZPD.',
                'q' => 'The gap between independent ability and assisted ability is the:',
                'options' => ['Schema', 'Zone of Proximal Development', 'Object permanence', 'Conservation'], 'correct' => 1,
                'exp' => "The ZPD is Vygotsky's gap between solo and assisted performance.",
            ],
            [
                'cat' => 'Developmental Psychology', 'title' => 'Prenatal Development', 'difficulty' => 'BEGINNER',
                'definition' => 'The stages of development from conception to birth.',
                'key_points' => 'Three stages: germinal (0–2 weeks), embryonic (2–8 weeks, organ formation), fetal (8 weeks–birth). Teratogens (alcohol, drugs, infections) can cause harm, especially in the embryonic stage.',
                'example' => 'Maternal alcohol use can cause Fetal Alcohol Syndrome — an example of a teratogen effect.',
                'q' => 'Environmental agents that cause harm during prenatal development are called:',
                'options' => ['Genes', 'Teratogens', 'Hormones', 'Neurotransmitters'], 'correct' => 1,
                'exp' => 'Teratogens are harmful agents affecting prenatal development.',
            ],
            [
                'cat' => 'Developmental Psychology', 'title' => 'Temperament', 'difficulty' => 'BEGINNER',
                'definition' => 'The biologically based, early-appearing individual differences in emotional reactivity and self-regulation.',
                'key_points' => 'Thomas and Chess identified three types: easy, difficult, and slow-to-warm-up. Goodness of fit between temperament and environment predicts adjustment.',
                'example' => 'An infant who adapts easily to new situations and has regular routines has an "easy" temperament.',
                'q' => 'The match between a child\'s temperament and their environment is called:',
                'options' => ['Attachment', 'Goodness of fit', 'Scaffolding', 'Assimilation'], 'correct' => 1,
                'exp' => 'Goodness of fit describes the temperament-environment match.',
            ],

            // ───────────── Social Psychology (more) ─────────────
            [
                'cat' => 'Social Psychology', 'title' => 'Attribution Theory', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'How people explain the causes of their own and others\' behavior.',
                'key_points' => 'Internal (dispositional) vs. external (situational) attributions. Fundamental attribution error: overestimating personality and underestimating the situation for others. Self-serving bias. Actor-observer bias.',
                'example' => 'Assuming a late classmate is "lazy" rather than "stuck in traffic" is the fundamental attribution error.',
                'q' => 'Overestimating personality factors in others\' behavior is the:',
                'options' => ['Self-serving bias', 'Fundamental attribution error', 'Halo effect', 'Just-world bias'], 'correct' => 1,
                'exp' => 'The fundamental attribution error overemphasizes disposition over situation.',
            ],
            [
                'cat' => 'Social Psychology', 'title' => 'Attitudes and Persuasion', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Evaluations of people, objects, or ideas, and how they can be changed.',
                'key_points' => 'ABC components: Affective, Behavioral, Cognitive. Elaboration Likelihood Model: central route (logic) vs. peripheral route (cues). Foot-in-the-door and door-in-the-face techniques.',
                'example' => 'Agreeing to a small request first, making you more likely to agree to a bigger one, is foot-in-the-door.',
                'q' => 'Persuasion through careful evaluation of arguments uses the ___ route.',
                'options' => ['peripheral', 'central', 'emotional', 'heuristic'], 'correct' => 1,
                'exp' => 'The central route to persuasion relies on the strength of arguments.',
            ],
            [
                'cat' => 'Social Psychology', 'title' => 'Prejudice and Discrimination', 'difficulty' => 'BEGINNER',
                'definition' => 'Prejudice is a negative attitude toward a group; discrimination is negative behavior toward its members.',
                'key_points' => 'Stereotype (belief) → prejudice (attitude) → discrimination (behavior). In-group bias and out-group homogeneity. Contact hypothesis reduces prejudice. Realistic conflict theory.',
                'example' => 'Refusing to hire someone because of their ethnicity is discrimination.',
                'q' => 'A negative attitude (not behavior) directed at a social group is:',
                'options' => ['Stereotype', 'Prejudice', 'Discrimination', 'Scapegoating'], 'correct' => 1,
                'exp' => 'Prejudice is the attitude; discrimination is the behavior.',
            ],
            [
                'cat' => 'Social Psychology', 'title' => 'Prosocial Behavior and the Bystander Effect', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Helping behavior and the factors that increase or decrease it.',
                'key_points' => 'Bystander effect: the more bystanders, the less likely any one helps. Diffusion of responsibility. Pluralistic ignorance. Latané and Darley\'s decision model. Studied after the Kitty Genovese case.',
                'example' => 'In a crowded street, people may assume "someone else will help" — diffusion of responsibility.',
                'q' => 'The tendency to help less when others are present is the:',
                'options' => ['Halo effect', 'Bystander effect', 'Conformity effect', 'Mere exposure effect'], 'correct' => 1,
                'exp' => 'The bystander effect describes reduced helping as group size increases.',
            ],

            // ───────────── Ethics & PH Psychology Law (more) ─────────────
            [
                'cat' => 'Ethics & PH Psychology Law', 'title' => 'RA 11036 — Philippine Mental Health Act', 'difficulty' => 'BEGINNER',
                'definition' => 'The 2018 law that establishes a national mental health policy and protects the rights of persons with mental health conditions.',
                'key_points' => 'Integrates mental health into the healthcare system. Protects patient rights, confidentiality, and informed consent. Promotes mental health in schools and workplaces. Reduces stigma.',
                'example' => 'A school implementing mental health programs for students aligns with RA 11036.',
                'q' => 'RA 11036 is known as the:',
                'options' => ['Psychology Act', 'Mental Health Act', 'Data Privacy Act', 'Anti-Bullying Act'], 'correct' => 1,
                'exp' => 'RA 11036 is the Philippine Mental Health Act of 2018.',
            ],
            [
                'cat' => 'Ethics & PH Psychology Law', 'title' => 'RA 10173 — Data Privacy Act', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'The 2012 law protecting the privacy of personal and sensitive information, including psychological data.',
                'key_points' => 'Personal information must be collected lawfully and processed fairly. Data subjects have rights to access, correct, and object. Health and psychological records are "sensitive personal information." Overseen by the National Privacy Commission.',
                'example' => 'Securing client test records and limiting access protects sensitive personal information under RA 10173.',
                'q' => 'Psychological test records are classified under RA 10173 as:',
                'options' => ['Public information', 'Sensitive personal information', 'Anonymous data', 'Open data'], 'correct' => 1,
                'exp' => 'Health and psychological data are sensitive personal information.',
            ],
            [
                'cat' => 'Ethics & PH Psychology Law', 'title' => 'Confidentiality and Its Limits', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'The ethical duty to protect client information, and the situations where it may be ethically broken.',
                'key_points' => 'Limits: imminent danger to self or others (duty to warn/protect, Tarasoff), suspected child abuse, court orders. Always inform clients of these limits during informed consent.',
                'example' => 'A psychologist may break confidentiality if a client makes a credible threat to harm another person.',
                'q' => 'Which situation can justify breaking confidentiality?',
                'options' => ['Client is sad', 'Imminent danger to others', 'Client disagrees with you', 'Routine record-keeping'], 'correct' => 1,
                'exp' => 'Imminent danger to self or others can justify breaking confidentiality.',
            ],

            // ═════════════ PSYCHOLOGIST (BLEPP) ADVANCED SUBJECTS ═════════════

            // ───────────── Advanced Psychological Assessment ─────────────
            [
                'cat' => 'Advanced Psychological Assessment', 'title' => 'Clinical Interviewing', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'A structured or semi-structured conversation used to gather history, observe behavior, and form clinical hypotheses.',
                'key_points' => 'Types: structured (e.g., SCID), semi-structured, unstructured. Goals: rapport, history-taking, mental status. Skills: open vs. closed questions, reflection, clarification. Always integrate with collateral and test data.',
                'example' => 'A psychologist uses a semi-structured interview to assess a client for major depressive disorder before administering tests.',
                'q' => 'Which interview type uses a fixed set of questions in a set order to maximize reliability?',
                'options' => ['Unstructured', 'Structured', 'Projective', 'Informal'], 'correct' => 1,
                'exp' => 'Structured interviews follow a fixed format, increasing reliability and comparability.',
            ],
            [
                'cat' => 'Advanced Psychological Assessment', 'title' => 'The Mental Status Examination (MSE)', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'A systematic snapshot of a client\'s current cognitive and emotional functioning during an interview.',
                'key_points' => 'Domains: appearance, behavior, speech, mood/affect, thought process/content, perception, cognition, insight, judgment. Detects psychosis, mood, and cognitive issues.',
                'example' => 'Noting flat affect, tangential speech, and auditory hallucinations during the MSE points toward a psychotic disorder.',
                'q' => 'Which MSE domain assesses whether a client recognizes that they have a problem?',
                'options' => ['Affect', 'Insight', 'Speech', 'Orientation'], 'correct' => 1,
                'exp' => 'Insight refers to the client\'s awareness and understanding of their own condition.',
            ],
            [
                'cat' => 'Advanced Psychological Assessment', 'title' => 'Projective Techniques', 'difficulty' => 'ADVANCED',
                'definition' => 'Assessment methods using ambiguous stimuli to elicit responses that reveal underlying needs, conflicts, and personality dynamics.',
                'key_points' => 'Rorschach Inkblot Test (Exner system), Thematic Apperception Test (TAT), sentence completion, projective drawings. Strengths: rich qualitative data. Criticisms: lower reliability/validity, subjective scoring.',
                'example' => 'A client\'s TAT stories consistently feature themes of abandonment, suggesting attachment-related concerns.',
                'q' => 'The Thematic Apperception Test (TAT) requires the client to:',
                'options' => ['Rate statements as true/false', 'Tell stories about pictures', 'Solve logic puzzles', 'Describe inkblots'], 'correct' => 1,
                'exp' => 'In the TAT, clients create stories about ambiguous pictures, projecting their inner dynamics.',
            ],
            [
                'cat' => 'Advanced Psychological Assessment', 'title' => 'Neuropsychological Assessment', 'difficulty' => 'ADVANCED',
                'definition' => 'Evaluation of brain–behavior relationships to detect cognitive impairment from injury, disease, or developmental conditions.',
                'key_points' => 'Domains: attention, memory, language, executive function, visuospatial skills, motor. Tools: Halstead-Reitan, Luria-Nebraska, WMS, Trail Making Test. Used for dementia, TBI, and localization.',
                'example' => 'Poor performance on the Trail Making Test B may indicate executive dysfunction.',
                'q' => 'Neuropsychological tests are primarily used to assess:',
                'options' => ['Vocational interests', 'Brain–behavior relationships', 'Career aptitude', 'Social attitudes'], 'correct' => 1,
                'exp' => 'Neuropsychological assessment examines how brain functioning relates to behavior and cognition.',
            ],
            [
                'cat' => 'Advanced Psychological Assessment', 'title' => 'Integrative Report Writing', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Synthesizing interview, observation, and multiple test results into a coherent, clinically useful psychological report.',
                'key_points' => 'Integrate data from multiple sources; avoid relying on a single test. Include referral question, methods, results, integration, diagnosis, and recommendations. Write for the intended audience and protect confidentiality.',
                'example' => 'A report weaves WAIS, MMPI-2, and interview findings into one narrative answering the referral question.',
                'q' => 'A key principle of sound psychological report writing is to:',
                'options' => ['Rely on one test only', 'Integrate multiple data sources', 'Omit recommendations', 'Use only raw scores'], 'correct' => 1,
                'exp' => 'Good reports integrate convergent evidence from several sources rather than a single test.',
            ],

            // ───────────── Counseling and Psychotherapy ─────────────
            [
                'cat' => 'Counseling and Psychotherapy', 'title' => 'Person-Centered Therapy', 'difficulty' => 'BEGINNER',
                'definition' => 'Carl Rogers\' humanistic approach in which the client\'s growth is fostered by a supportive therapeutic relationship.',
                'key_points' => 'Three core conditions: congruence (genuineness), unconditional positive regard, and empathic understanding. Non-directive; the client leads. Goal: become a fully functioning person.',
                'example' => 'The counselor reflects feelings and accepts the client without judgment, helping them explore their self-concept.',
                'q' => 'The three core conditions of person-centered therapy were proposed by:',
                'options' => ['Aaron Beck', 'Carl Rogers', 'Fritz Perls', 'B. F. Skinner'], 'correct' => 1,
                'exp' => 'Carl Rogers identified congruence, unconditional positive regard, and empathy as core conditions.',
            ],
            [
                'cat' => 'Counseling and Psychotherapy', 'title' => 'Cognitive Behavioral Therapy (CBT)', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'A structured, present-focused therapy that targets the links between thoughts, feelings, and behaviors.',
                'key_points' => 'Beck\'s cognitive distortions and automatic thoughts; Ellis\' REBT (ABC model: Activating event–Belief–Consequence). Techniques: cognitive restructuring, behavioral activation, exposure, homework.',
                'example' => 'A client learns to challenge the automatic thought "I always fail" with evidence-based balanced thinking.',
                'q' => 'In Ellis\' REBT, the "B" in the ABC model stands for:',
                'options' => ['Behavior', 'Belief', 'Baseline', 'Boundary'], 'correct' => 1,
                'exp' => 'In the ABC model, B refers to the Belief about the activating event, which drives the consequence.',
            ],
            [
                'cat' => 'Counseling and Psychotherapy', 'title' => 'Psychodynamic Therapy', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Therapy aimed at increasing insight into unconscious conflicts rooted in early experience.',
                'key_points' => 'Techniques: free association, dream analysis, interpretation. Concepts: transference, countertransference, resistance, working through. Briefer than classical psychoanalysis.',
                'example' => 'A client begins reacting to the therapist as they did to a parent — an example of transference.',
                'q' => 'When a client unconsciously redirects feelings about a parent onto the therapist, this is called:',
                'options' => ['Resistance', 'Transference', 'Catharsis', 'Congruence'], 'correct' => 1,
                'exp' => 'Transference is the projection of feelings from past relationships onto the therapist.',
            ],
            [
                'cat' => 'Counseling and Psychotherapy', 'title' => 'Group and Family Therapy', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Therapeutic approaches that treat clients within their interpersonal systems rather than individually.',
                'key_points' => 'Yalom\'s therapeutic factors (universality, instillation of hope, cohesion). Family systems: Bowen, structural (Minuchin), strategic. Focus on roles, boundaries, and communication patterns.',
                'example' => 'A family therapist restructures rigid boundaries between an overinvolved parent and child.',
                'q' => 'Structural family therapy, which focuses on boundaries and subsystems, was developed by:',
                'options' => ['Murray Bowen', 'Salvador Minuchin', 'Irvin Yalom', 'Virginia Satir'], 'correct' => 1,
                'exp' => 'Salvador Minuchin developed structural family therapy.',
            ],
            [
                'cat' => 'Counseling and Psychotherapy', 'title' => 'The Counseling Process and Stages', 'difficulty' => 'BEGINNER',
                'definition' => 'The sequence of phases through which a counseling relationship typically progresses.',
                'key_points' => 'Stages: rapport/relationship building, assessment, goal setting, intervention, termination. Core skills: attending, active listening, reflection, summarizing. Termination should be planned.',
                'example' => 'Before ending therapy, the counselor reviews progress and prepares the client for termination.',
                'q' => 'Building rapport and a working alliance occurs mainly in which counseling stage?',
                'options' => ['Termination', 'Early/relationship-building stage', 'Follow-up', 'Intervention'], 'correct' => 1,
                'exp' => 'Rapport and the therapeutic alliance are established early, in the relationship-building stage.',
            ],
            [
                'cat' => 'Counseling and Psychotherapy', 'title' => 'Ethics in Counseling', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'The professional and ethical standards that govern the counseling relationship.',
                'key_points' => 'Informed consent, confidentiality and its limits, avoiding dual/multiple relationships, competence and scope of practice, beneficence and non-maleficence. Refer when beyond competence.',
                'example' => 'A counselor refers a client needing specialized eating-disorder treatment that is outside their competence.',
                'q' => 'Avoiding a situation where the counselor is also the client\'s business partner addresses which ethical concern?',
                'options' => ['Informed consent', 'Dual relationships', 'Record-keeping', 'Fees'], 'correct' => 1,
                'exp' => 'Being both counselor and business partner is a dual (multiple) relationship that risks harm and impaired objectivity.',
            ],

            // ───────────── Advanced Abnormal Psychology ─────────────
            [
                'cat' => 'Advanced Abnormal Psychology', 'title' => 'Bipolar and Related Disorders', 'difficulty' => 'ADVANCED',
                'definition' => 'Mood disorders marked by episodes of mania/hypomania, often alternating with depression.',
                'key_points' => 'Bipolar I: at least one manic episode. Bipolar II: hypomania + major depression (no full mania). Cyclothymia: chronic fluctuating sub-threshold symptoms. Mania: elevated mood, grandiosity, decreased need for sleep.',
                'example' => 'A person with Bipolar I has a manic week of grandiosity and impulsive spending, later followed by depression.',
                'q' => 'The key feature distinguishing Bipolar I from Bipolar II is the presence of:',
                'options' => ['A full manic episode', 'Only depression', 'Hallucinations', 'Panic attacks'], 'correct' => 0,
                'exp' => 'Bipolar I requires at least one full manic episode; Bipolar II involves hypomania, not full mania.',
            ],
            [
                'cat' => 'Advanced Abnormal Psychology', 'title' => 'Dissociative Disorders', 'difficulty' => 'ADVANCED',
                'definition' => 'Disorders involving disruptions in consciousness, memory, identity, or perception, often linked to trauma.',
                'key_points' => 'Dissociative Identity Disorder (DID), dissociative amnesia, depersonalization/derealization disorder. Strongly associated with severe early trauma.',
                'example' => 'A client cannot recall personal information beyond what ordinary forgetting explains — dissociative amnesia.',
                'q' => 'Which disorder involves two or more distinct personality states?',
                'options' => ['Dissociative amnesia', 'Dissociative Identity Disorder', 'Depersonalization disorder', 'PTSD'], 'correct' => 1,
                'exp' => 'Dissociative Identity Disorder (DID) features two or more distinct identity/personality states.',
            ],
            [
                'cat' => 'Advanced Abnormal Psychology', 'title' => 'Somatic Symptom and Related Disorders', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Disorders featuring physical symptoms or health anxiety that cause distress and impairment.',
                'key_points' => 'Somatic symptom disorder, illness anxiety disorder, conversion disorder (functional neurological symptom disorder), factitious disorder. Symptoms are not intentionally produced (except factitious/malingering).',
                'example' => 'A client experiences sudden, medically unexplained paralysis after a stressor — conversion disorder.',
                'q' => 'Excessive worry about having a serious illness despite few or no symptoms best describes:',
                'options' => ['Conversion disorder', 'Illness anxiety disorder', 'Factitious disorder', 'Malingering'], 'correct' => 1,
                'exp' => 'Illness anxiety disorder involves preoccupation with having or acquiring a serious illness.',
            ],
            [
                'cat' => 'Advanced Abnormal Psychology', 'title' => 'Feeding and Eating Disorders', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Disorders characterized by disturbed eating behaviors and distorted attitudes about weight and body shape.',
                'key_points' => 'Anorexia nervosa (restriction, low body weight, fear of weight gain), bulimia nervosa (binge + compensatory purging), binge-eating disorder (binge without compensation). High medical risk.',
                'example' => 'A client binges then induces vomiting to prevent weight gain — bulimia nervosa.',
                'q' => 'Recurrent binge eating followed by compensatory purging characterizes:',
                'options' => ['Anorexia nervosa', 'Bulimia nervosa', 'Binge-eating disorder', 'Pica'], 'correct' => 1,
                'exp' => 'Bulimia nervosa involves binge eating with compensatory behaviors such as purging.',
            ],
            [
                'cat' => 'Advanced Abnormal Psychology', 'title' => 'Substance-Related and Addictive Disorders', 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Disorders involving problematic use of substances leading to clinically significant impairment.',
                'key_points' => 'Concepts: tolerance, withdrawal, craving, loss of control. Distinguish use, intoxication, withdrawal, and substance use disorder. Gambling disorder is the non-substance addictive disorder in the DSM-5.',
                'example' => 'Needing increasing amounts of a substance to get the same effect demonstrates tolerance.',
                'q' => 'Needing more of a substance over time to achieve the same effect is called:',
                'options' => ['Withdrawal', 'Tolerance', 'Craving', 'Relapse'], 'correct' => 1,
                'exp' => 'Tolerance is the need for increased amounts to achieve the desired effect.',
            ],

            // ───────────── Advanced Theories of Personality ─────────────
            [
                'cat' => 'Advanced Theories of Personality', 'title' => "Object Relations Theory", 'difficulty' => 'ADVANCED',
                'definition' => 'A psychodynamic approach emphasizing internalized relationships ("objects") formed in early childhood.',
                'key_points' => 'Key figures: Melanie Klein, Donald Winnicott (good-enough mother, transitional object), Margaret Mahler (separation-individuation). Internal representations shape adult relationships.',
                'example' => 'A child\'s security blanket serves as a transitional object in Winnicott\'s theory.',
                'q' => 'The concept of the "good-enough mother" and the transitional object comes from:',
                'options' => ['Melanie Klein', 'Donald Winnicott', 'Heinz Kohut', 'Carl Jung'], 'correct' => 1,
                'exp' => 'Donald Winnicott introduced the "good-enough mother" and the transitional object.',
            ],
            [
                'cat' => 'Advanced Theories of Personality', 'title' => "Kohut's Self Psychology", 'difficulty' => 'ADVANCED',
                'definition' => 'A psychoanalytic theory focusing on the development of a cohesive self through empathic relationships.',
                'key_points' => 'Self-objects meet needs for mirroring, idealization, and twinship. Empathic failures can produce narcissistic vulnerability. Therapy provides corrective empathic attunement.',
                'example' => 'A client who needs constant admiration may have unmet mirroring self-object needs.',
                'q' => 'Self psychology, centered on self-objects and mirroring, was developed by:',
                'options' => ['Heinz Kohut', 'Erik Erikson', 'Alfred Adler', 'Karen Horney'], 'correct' => 0,
                'exp' => 'Heinz Kohut founded self psychology.',
            ],
            [
                'cat' => 'Advanced Theories of Personality', 'title' => "Horney's Theory of Neurosis", 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Karen Horney\'s view that personality and neurosis arise from basic anxiety and interpersonal coping styles.',
                'key_points' => 'Basic anxiety from childhood insecurity. Three neurotic trends: moving toward people (compliance), against people (aggression), and away from people (withdrawal). Critiqued Freud\'s penis envy with "womb envy."',
                'example' => 'A person who copes by always pleasing and seeking approval is "moving toward people."',
                'q' => 'Horney\'s three neurotic trends are moving toward, against, and ___ people.',
                'options' => ['below', 'away from', 'around', 'above'], 'correct' => 1,
                'exp' => 'The three trends are moving toward, against, and away from people.',
            ],
            [
                'cat' => 'Advanced Theories of Personality', 'title' => "Existential Personality Theory", 'difficulty' => 'ADVANCED',
                'definition' => 'An approach emphasizing freedom, responsibility, meaning, and confronting the "givens" of existence.',
                'key_points' => 'Key figures: Rollo May, Viktor Frankl (logotherapy/meaning), Irvin Yalom (death, freedom, isolation, meaninglessness). Anxiety is part of authentic living.',
                'example' => 'Frankl\'s logotherapy helps a client find meaning even amid unavoidable suffering.',
                'q' => 'Logotherapy, which centers on the search for meaning, was founded by:',
                'options' => ['Rollo May', 'Viktor Frankl', 'Irvin Yalom', 'Ludwig Binswanger'], 'correct' => 1,
                'exp' => 'Viktor Frankl founded logotherapy, emphasizing meaning as the primary human motivation.',
            ],
            [
                'cat' => 'Advanced Theories of Personality', 'title' => "Allport's Functional Autonomy", 'difficulty' => 'INTERMEDIATE',
                'definition' => 'Gordon Allport\'s idea that adult motives can become independent of their original childhood origins.',
                'key_points' => 'Functional autonomy: a behavior once done for one reason continues for its own sake. Distinguishes proprium (the "self") and emphasizes the present over the past, unlike Freud.',
                'example' => 'A man who first fished to feed his family keeps fishing for enjoyment long after — functional autonomy.',
                'q' => 'The concept of functional autonomy of motives was proposed by:',
                'options' => ['Raymond Cattell', 'Gordon Allport', 'Hans Eysenck', 'Sigmund Freud'], 'correct' => 1,
                'exp' => 'Gordon Allport proposed that adult motives can become functionally autonomous from their origins.',
            ],
        ];

        foreach ($topics as $t) {
            $topic = Topic::create([
                'category_id' => $catModels[$t['cat']]->id,
                'title' => $t['title'],
                'slug' => Str::slug($t['title']),
                'difficulty' => $t['difficulty'],
                'definition' => $t['definition'],
                'key_points' => $t['key_points'],
                'example' => $t['example'],
            ]);

            QuizQuestion::create([
                'topic_id' => $topic->id,
                'question' => $t['q'],
                'options' => $t['options'],
                'correct_index' => $t['correct'],
                'explanation' => $t['exp'],
            ]);
        }
    }
}
