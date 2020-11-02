CREATE TABLE mensalidade (
	id INT PRIMARY KEY AUTO_INCREMENT,
	nro_mensalidade INT NOT NULL,
	data_vencimento DATETIME NOT NULL,
	contrato_id INT NOT NULL,
	status VARCHAR(10) NOT NULL DEFAULT 'NÃO PAGA',
	FOREIGN KEY (contrato_id) REFERENCES contrato(id),
	CONSTRAINT CHECK (status = 'PAGA' OR status = 'NÃO PAGA')
)
