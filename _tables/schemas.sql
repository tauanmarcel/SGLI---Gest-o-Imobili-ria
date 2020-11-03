CREATE TABLE IF NOT EXISTS locatario (
	id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    fone VARCHAR(20) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT NOW(),
    updated_at DATETIME NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS locador (
	id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    fone VARCHAR(20) NOT NULL,
    dia_repasse INT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT NOW(),
    updated_at DATETIME NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS imovel (
	id INT PRIMARY KEY AUTO_INCREMENT,
    codigo_api VARCHAR(20) NULL,
    bairro VARCHAR(150) NOT NULL,
    cidade VARCHAR(150) NOT NULL,
    locador_id INT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT NOW(),
    updated_at DATETIME NOT NULL DEFAULT NOW(),
    FOREIGN KEY (locador_id) REFERENCES locador(id)
);

CREATE TABLE IF NOT EXISTS contrato (
	id INT PRIMARY KEY AUTO_INCREMENT,
	data_inicio  DATETIME NOT NULL,
	data_fim  DATETIME NOT NULL,
	taxa_admin NUMERIC(11,2) NOT NULL,
	vlr_aluguel  NUMERIC(11,2) NOT NULL,
	vlr_condominio NUMERIC(11,2) NOT NULL,
	vlr_iptu  NUMERIC(11,2) NOT NULL,
	imovel_id INT NOT NULL,
	locador_id INT NOT NULL,
	locatario_id INT NOT NULL,
	created_at DATETIME NOT NULL DEFAULT NOW(),
    updated_at DATETIME NOT NULL DEFAULT NOW(),
	FOREIGN KEY (imovel_id) REFERENCES imovel(id),
	FOREIGN KEY (locador_id) REFERENCES locador(id),
	FOREIGN KEY (locatario_id) REFERENCES locatario(id)
);

CREATE TABLE IF NOT EXISTS mensalidade (
	id INT PRIMARY KEY AUTO_INCREMENT,
	nro_mensalidade INT NOT NULL,
	data_vencimento DATETIME NOT NULL,
	vlr_mensalidade NUMERIC(11,2) NOT NULL,
	contrato_id INT NOT NULL,
	status VARCHAR(10) NOT NULL DEFAULT 'NÃO PAGA',
	created_at DATETIME NOT NULL DEFAULT NOW(),
    updated_at DATETIME NOT NULL DEFAULT NOW(),
	FOREIGN KEY (contrato_id) REFERENCES contrato(id),
	CONSTRAINT CHECK (status = 'PAGA' OR status = 'NÃO PAGA')
);

CREATE TABLE IF NOT EXISTS repasse  (
	id INT PRIMARY KEY AUTO_INCREMENT,
	nro_repasse INT NOT NULL,
	data_repasse DATETIME NOT NULL,
	vlr_repasse NUMERIC(11,2) NOT NULL,
	status VARCHAR(15) NOT NULL DEFAULT 'NÃO REALIZADO',
	contrato_id INT NOT NULL,
	created_at DATETIME NOT NULL DEFAULT NOW(),
    updated_at DATETIME NOT NULL DEFAULT NOW(),
    FOREIGN KEY (contrato_id) REFERENCES contrato(id),
    CONSTRAINT CHECK (status = 'REALIZADO' OR status = 'NÃO REALIZADO')
);
