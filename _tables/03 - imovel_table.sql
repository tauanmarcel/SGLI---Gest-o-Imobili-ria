CREATE TABLE imovel (
	id INT PRIMARY KEY AUTO_INCREMENT,
    codigo_api VARCHAR(20) NULL,
    bairro VARCHAR(150) NOT NULL,
    cidade VARCHAR(150) NOT NULL,
    locador_id INT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT NOW(),
    updated_at DATETIME NOT NULL DEFAULT NOW(),
    FOREIGN KEY (locador_id) REFERENCES locador(id)
)