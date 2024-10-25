import tkinter as tk
import webbrowser

def open_link():
    url = 'https://www.waters.com/nextgen/us/en.html'
    webbrowser.open(url)

# Create the main window
root = tk.Tk()
root.title("Open Link Example")
root.geometry("300x200")

# Create a button that opens the link
open_button = tk.Button(root, text="Open Link", command=open_link)
open_button.pack(pady=20)

# Start the GUI event loop
root.mainloop()
