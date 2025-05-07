from aiosmtpd.controller import Controller
from pprint import pprint

class Handler:
    async def handle_DATA(self, server, session, envelope):
        print('->', *envelope.rcpt_tos)
        print(envelope.content.decode('utf-8'))
        return '250 OK'

handler = Handler()
controller = Controller(handler)
controller.hostname = 'localhost'
controller.port = 25

controller.start()
input("Server started. Press Return to quit.\n")
controller.stop()
