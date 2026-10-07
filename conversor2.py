import sys 
from pdf2docx import Converter

dato_recibido = sys.argv[1]

destino = dato_recibido.replace(".docx",".pdf")

conversor = Converter(dato_recibido)

conversor.convert(destino)

conversor.close
