import subprocess
import sys

PYTHON_INTERPRETER = "../pymilvus/venv/bin/python3"

tests = (
    'example',
    'example_index'
)

if __name__ == '__main__':
    for test in tests:
        if sys.argv[1:] and test not in sys.argv[1:]:
            continue

        print(f"Running test '{test}'...")
        python_out = subprocess.getoutput(f'{PYTHON_INTERPRETER} {test}.py')
        php_out = subprocess.getoutput(f'php {test}.php')

        if python_out != php_out:
            out_f = f'{test}_out.txt'
            with open(out_f, 'w') as f:
                f.write('--- python ---\n')
                f.write(python_out)
                f.write('\n\n--- php ---\n')
                f.write(php_out)
            print(f'[Error] Output mismatch for {test}. Output in {out_f}')
            continue

        print(f'[Success] Output match for {test}')
