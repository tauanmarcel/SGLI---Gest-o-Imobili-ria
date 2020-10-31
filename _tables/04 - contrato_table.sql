CREATE TABLE contrato (
	id INT PRIMARY KEY AUTO_INCREMENT,
	data_inicio  DATETIME NOT NULL,
	data_fim  DATETIME NOT NULL,
	taxa_admin NUMERIC(11,0) NOT NULL,
	vlr_aluguel  NUMERIC(11,0) NOT NULL,
	vlr_condominio NUMERIC(11,0) NOT NULL,
	vlr_iptu  NUMERIC(11,0) NOT NULL,
	imovel_id INT NOT NULL,
	locador_id INT NOT NULL,
	locatario_id INT NOT NULL,
	created_at DATETIME NOT NULL DEFAULT NOW(),
    updated_at DATETIME NOT NULL DEFAULT NOW(),
	FOREIGN KEY (imovel_id) REFERENCES imovel(id),
	FOREIGN KEY (locador_id) REFERENCES locador(id),
	FOREIGN KEY (locatario_id) REFERENCES locatario(id)
)
