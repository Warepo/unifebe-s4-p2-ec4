import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.Statement;
import java.sql.ResultSet;
import java.sql.DatabaseMetaData;
import java.sql.ResultSetMetaData;
import java.util.Scanner;
import java.util.ArrayList;

/*
 * Antes de iniciar o exercicio, execute o programa e tente compreender a logica do codigo abaixo
 *
 **/
public class Principal {
   		 public static void main(String[] args) {
   		 		String SQL = "";
   		 		ArrayList<String> list=new ArrayList<String>();
   		 		String database = "teste"; //nome da base de dados
       			try{
       					/*Conectando ao Servidor de Banco de Dados*/
            			Class.forName("com.mysql.jdbc.Driver");
						Connection Conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/","root","aluno");//cria conex�o com o servidor
						Statement s= Conn.createStatement();

            			System.out.println("BD Conectado");
						/*****************************************/

						/*Criando Base de Dados*/

						DatabaseMetaData meta = Conn.getMetaData();//Seleciona todas as bases de dados do servidor
         				ResultSet rs = meta.getCatalogs(); //faz um ponteiro para cada database
         				while (rs.next()) { //enquanto houverem databases
            				String listofDatabases=rs.getString("TABLE_CAT"); //resgata o nome da database
             				list.add(listofDatabases); //adiciona a uma lista
         				}
        				if(list.contains(database)){ //se a base de dados 'teste' j� existir
         					s.executeUpdate("DROP DATABASE "+database);//deleta database existente
         					s.executeUpdate("CREATE DATABASE "+database);//cria uma nova database com o mesmo nome
            				System.out.println("Database criada!");
             			}
             			else{
             				s.executeUpdate("CREATE DATABASE "+database);//cria uma nova database de nome 'teste'
            				System.out.println("Database criada!");
             			}
            			rs.close();//fecha conex�o com a lista de bases de dados

            			Conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/"+database,"root","aluno");//cria conex�o com a base de dados criada
						s= Conn.createStatement(); //cria objeto Statement
						System.out.println("Conectado � base de dados "+database);

						/*****************************************/

						SQL = new String(); //limpando vari�vel para receber novo valor

						/* Criando Tabela */
						SQL = "CREATE TABLE Alunos (nome varchar(50),curso varchar(50),fase int,cpf varchar(50) PRIMARY KEY)";
                   		s.executeUpdate(SQL); //executa

                   		System.out.println("Tabela 'Alunos' Criada!");
                   		/*****************************************/

						SQL = new String(); //limpando vari�vel para receber novo valor

						/*Inserindo Valores*/
						String nome = "Maria";
						String curso = "Informatica";
						String cpf = "123.456.789-10";

						Statement stmt = Conn.createStatement(); //cria objeto do tipo Statement
						// Tabela a ser analisada
						String tabela = "Alunos";
						ResultSet rset = stmt.executeQuery("SELECT * from "+tabela); //cria ponteiro para a tabela

						String valores = "";
						String campos="";
						Scanner leia = new Scanner(System.in);

						ResultSetMetaData rsmd = rset.getMetaData(); //recupera informacoes da tabela

						// retorna o numero total de colunas
							int numColumns = rsmd.getColumnCount();
							System.out.println("Total de Colunas = " + numColumns);

						// loop para recuperar os metadados de cada coluna (nome da coluna, tipo do campo, etc)
						for (int i=0; i<numColumns; i++) {
								System.out.print("Insira o dado para a coluna=" + rsmd.getColumnName (i + 1)+": ");

								if((rsmd.getColumnTypeName (i + 1)).equals("INT")){ //se o campo for INT
									if(i!=numColumns-1){ //se n�o for a �ltima coluna
										valores = valores+leia.next()+",";//concatena apenas uma virgula(tipo INT nao precisa de aspas)
										campos = campos+rsmd.getColumnName (i + 1)+",";//recupera nome do campo (coluna) e insere uma virgula
									}
									else{//se for a ultima coluna
										valores = valores+leia.next();//le o dado
										campos = campos+rsmd.getColumnName (i + 1);//recupera nome da coluna
									}
								}else{ //se nao for INT
									if(i!=numColumns-1){//se n�o for a ultima coluna
										valores = valores+"'"+leia.nextLine()+"',"; //concatena uma aspa simples seguida de virgula
										campos = campos+rsmd.getColumnName (i + 1)+",";//recupera nome da coluna e insere virgula
									}
									else{ //se for ultimo valor
										valores = valores+"'"+leia.next()+"'"; //concatena somente aspas simples, sem virgula
										campos = campos+rsmd.getColumnName (i + 1);//recupera nome da coluna
									}
								}
						}

							SQL = "INSERT INTO "+tabela+" ("+campos+") VALUES ("+valores+")";//INSERT
							System.out.println(SQL);//'DEBUG'


						s.executeUpdate(SQL); //executa
						System.out.println("Dados Inseridos!");
						/*****************************************/

						SQL = new String(); //limpando vari�vel para receber novo valor

						/*Pesquisando um determinado valor*/
						SQL = "SELECT * from Alunos";

						rs = s.executeQuery(SQL);//criando ponteiro para a tabela
						while (rs.next()) {//enquanto houver linhas
   						 	System.out.println(rs.getString("nome"));//recupera os dados da coluna 'nome'
    					 	System.out.println(rs.getString("curso"));//recupera os dados da coluna 'curso'
						}
						/**********************************/
        			}catch(Exception e){//exce��o geral. Crie mais catchs do tipo SQLException etc para capturar exce��es mais eficazmente
           		 		System.err.println("Ocorreu um Erro!");
           		 		e.printStackTrace();
       			 }
   		 }
}

