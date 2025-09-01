-- Seed data for Company Ideas and Innovation Management System

-- Insert users with hashed passwords (password123 for all)
INSERT INTO `users` (`first_name`, `last_name`, `email`, `password_hash`, `role`, `status`) VALUES
-- Admin user
('Admin', 'System', 'admin@company.com', '$2y$10$T87hhBceqLWULt698A/Aheih4LugNqw4Qy5zM0HQwEhoOXEfC6moK', 'admin', 'active'),

-- Salarie users (employees)
('Marie', 'Dupont', 'marie.dupont@company.com', '$2y$10$T87hhBceqLWULt698A/Aheih4LugNqw4Qy5zM0HQwEhoOXEfC6moK', 'salarie', 'active'),
('Pierre', 'Martin', 'pierre.martin@company.com', '$2y$10$T87hhBceqLWULt698A/Aheih4LugNqw4Qy5zM0HQwEhoOXEfC6moK', 'salarie', 'active'),

-- Evaluateur users (evaluators)
('Sophie', 'Bernard', 'sophie.bernard@company.com', '$2y$10$T87hhBceqLWULt698A/Aheih4LugNqw4Qy5zM0HQwEhoOXEfC6moK', 'evaluateur', 'active'),
('Jean', 'Rousseau', 'jean.rousseau@company.com', '$2y$10$T87hhBceqLWULt698A/Aheih4LugNqw4Qy5zM0HQwEhoOXEfC6moK', 'evaluateur', 'active');

-- Insert themes
INSERT INTO `themes` (`name`, `description`, `is_active`) VALUES
('Innovation Digitale', 'Idées liées à la transformation numérique et aux nouvelles technologies', 1),
('Développement Durable', 'Initiatives pour réduire l\'impact environnemental et promouvoir la durabilité', 1),
('Amélioration des Processus', 'Optimisation des workflows et processus internes pour plus d\'efficacité', 1),
('Bien-être au Travail', 'Améliorer la qualité de vie et l\'environnement de travail des employés', 1);

-- Insert ideas
INSERT INTO `ideas` (`user_id`, `theme_id`, `title`, `description`, `status`) VALUES
-- Ideas from Marie Dupont (user_id = 2)
(2, 1, 'Application Mobile Interne', 'Développer une application mobile pour faciliter la communication interne et l\'accès aux documents d\'entreprise depuis n\'importe où.', 'under_review'),
(2, 3, 'Automatisation des Rapports', 'Mettre en place un système automatisé pour générer les rapports mensuels et réduire le temps de traitement manuel.', 'submitted'),

-- Ideas from Pierre Martin (user_id = 3)
(3, 2, 'Programme de Covoiturage', 'Créer une plateforme interne pour faciliter le covoiturage entre collègues et réduire l\'empreinte carbone.', 'accepted'),
(3, 4, 'Espace de Détente', 'Aménager un espace de détente avec plantes vertes et mobilier confortable pour les pauses des employés.', 'submitted'),
(3, 1, 'Chatbot IA pour Support IT', 'Implémenter un chatbot intelligent pour répondre aux questions courantes du support IT et réduire la charge de travail.', 'under_review');

-- Insert evaluations
INSERT INTO `evaluations` (`idea_id`, `evaluator_id`, `score`, `comment`) VALUES
-- Evaluations by Sophie Bernard (evaluator_id = 4)
(1, 4, 8, 'Excellente idée qui améliorerait vraiment la productivité mobile. Nécessite une étude de faisabilité technique.'),
(3, 4, 9, 'Initiative très pertinente pour l\'environnement. Facile à mettre en œuvre avec un impact positif immédiat.'),
(5, 4, 7, 'Concept intéressant mais nécessite des ressources importantes en développement IA.'),

-- Evaluations by Jean Rousseau (evaluator_id = 5)
(1, 5, 7, 'Bonne idée mais il faut s\'assurer de la sécurité des données sur mobile.'),
(3, 5, 8, 'Projet réalisable avec un bon ROI environnemental. Recommande un partenariat avec une plateforme existante.'),
(5, 5, 6, 'Intéressant mais peut-être trop complexe pour commencer. Suggère de commencer par une FAQ automatisée.');

-- Insert feedback from admin
INSERT INTO `feedback` (`idea_id`, `admin_id`, `message`) VALUES
(1, 1, 'Idée retenue pour étude approfondie. Merci de préparer un cahier des charges détaillé avec estimation budgétaire.'),
(3, 1, 'Excellente initiative ! Nous allons commencer par une phase pilote avec 50 volontaires. Pouvez-vous identifier des partenaires potentiels ?'),
(5, 1, 'Projet ambitieux. Nous suggérons de commencer par une version simplifiée avec FAQ automatisée avant d\'intégrer l\'IA.');

-- Update average scores for ideas (calculated from evaluations)
UPDATE `ideas` SET `avg_score` = (
    SELECT AVG(`score`) 
    FROM `evaluations` 
    WHERE `evaluations`.`idea_id` = `ideas`.`id`
) WHERE `id` IN (
    SELECT DISTINCT `idea_id` 
    FROM `evaluations`
);

-- Insert some idea attachments (optional)
INSERT INTO `idea_attachments` (`idea_id`, `file_path`, `original_name`, `file_size`, `mime_type`) VALUES
(1, 'uploads/ideas/1/mockup_app_mobile.pdf', 'Mockup Application Mobile.pdf', 2048576, 'application/pdf'),
(3, 'uploads/ideas/3/etude_covoiturage.docx', 'Étude de Marché Covoiturage.docx', 1024000, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

-- Show sample data
SELECT 'Users created:' as Info;
SELECT id, first_name, last_name, email, role FROM users;

SELECT 'Themes created:' as Info;
SELECT id, name, is_active FROM themes;

SELECT 'Ideas created:' as Info;
SELECT i.id, i.title, i.status, i.avg_score, u.first_name, u.last_name, t.name as theme_name 
FROM ideas i 
JOIN users u ON i.user_id = u.id 
JOIN themes t ON i.theme_id = t.id;

SELECT 'Evaluations created:' as Info;
SELECT e.id, e.score, i.title, u.first_name, u.last_name as evaluator_name 
FROM evaluations e 
JOIN ideas i ON e.idea_id = i.id 
JOIN users u ON e.evaluator_id = u.id;
