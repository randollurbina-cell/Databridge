import sys
from docx2pdf import convert 

dato_recibido = sys.argv[1]

convert(dato_recibido)

